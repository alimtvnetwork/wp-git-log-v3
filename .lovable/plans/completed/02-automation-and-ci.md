# Automation & CI Worker Tasks

Status: completed
Agent: Automation & CI Worker

## Micro-Tasks
1. Author `spec/linter-scripts/generate-spec-index.cjs`.
   - Write a node script stub that reads `spec/` directories and regenerates `spec/spec-index.md`.
2. Implement the CI gate in `.github/workflows/ci.yml`.
   - Add a step to fail PRs if tree health drops below 95/100 (can be a stub check step for now).
3. Ensure Node/npm dependencies are cached for the linter script.
