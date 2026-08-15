import { describe, it, expect, vi } from 'vitest';
import { apiClient, ApiError } from '../lib/api-client';
import axios from 'axios';
import MockAdapter from 'axios-mock-adapter';

describe('apiClient', () => {
  it('should correctly unwrap a success envelope', async () => {
    const mock = new MockAdapter(apiClient);
    
    // Simulate Laravel ApiResponse::success()
    mock.onGet('/test-success').reply(200, {
      Status: 200,
      Results: { foo: 'bar' }
    });

    const data = await apiClient.get('/test-success');
    expect(data).toEqual({ foo: 'bar' });
  });

  it('should intercept an ErrorEnvelope and throw a structured ApiError', async () => {
    const mock = new MockAdapter(apiClient);
    
    // Simulate Laravel ErrorEnvelope payload
    mock.onPost('/test-error').reply(403, {
      Results: [
        {
          code: 'GL-AUTHZ-PERMISSION-DENIED',
          trace_id: '1234-abcd',
          message: 'Permission denied',
        }
      ]
    });

    try {
      await apiClient.post('/test-error');
      expect.fail('Should have thrown an ApiError');
    } catch (error) {
      expect(error).toBeInstanceOf(ApiError);
      const apiError = error as ApiError;
      expect(apiError.envelope.ErrorCode).toBe('GL-AUTHZ-PERMISSION-DENIED');
      expect(apiError.envelope.TraceId).toBe('1234-abcd');
      expect(apiError.status).toBe(403);
    }
  });
});
