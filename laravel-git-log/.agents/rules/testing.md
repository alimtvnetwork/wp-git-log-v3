# Testing and Background Tasks

Do not leave long-running background servers (like `php artisan serve`, `npm run dev`, or HTTP servers) running indefinitely as daemon tasks (`IsDaemon: true`) after you are done testing unless explicitly requested by the user. 

If you need to spin up a server to test an endpoint or run an integration test, you MUST gracefully terminate the task (e.g., using the `manage_task` tool with action `kill`) immediately after the test succeeds or fails. Do not leave the task hanging in the background to clutter the user's workspace.
