const fs = require('fs');

function patch(file, replacer) {
    let content = fs.readFileSync(file, 'utf8');
    let newContent = replacer(content);
    if (content !== newContent) {
        fs.writeFileSync(file, newContent);
        console.log('Patched ' + file);
    }
}

// 1. src/pages/GitProfiles.tsx
// 2. src/pages/LogViewer.tsx
// 3. src/pages/PipelineDetail.tsx
// 4. src/pages/Pipelines.tsx
// 5. src/pages/Profiles.tsx
// 6. src/pages/Repos.tsx
// 7. src/pages/TraceViewer.tsx
// 8. src/types/trace-map.ts
// 9. laravel-git-log/app/Http/Controllers/LaneA/*.php

const filesToFix = [
    'src/pages/GitProfiles.tsx',
    'src/pages/LogViewer.tsx',
    'src/pages/PipelineDetail.tsx',
    'src/pages/Pipelines.tsx',
    'src/pages/Profiles.tsx',
    'src/pages/Repos.tsx',
    'src/pages/TraceViewer.tsx',
    'src/types/trace-map.ts',
    'laravel-git-log/app/Http/Controllers/LaneA/AppController.php',
    'laravel-git-log/app/Http/Controllers/LaneA/AppLinkController.php',
    'laravel-git-log/app/Http/Controllers/LaneA/AuditTrailController.php',
    'laravel-git-log/app/Http/Controllers/LaneA/GitProfileController.php',
    'laravel-git-log/app/Http/Controllers/LaneA/PermissionController.php'
];

for (let f of filesToFix) {
    if (!fs.existsSync(f)) continue;
    patch(f, c => {
        // Fix missing blank line before return
        c = c.replace(/([^\n])\n(\s*)(return\s+)/g, (match, p1, p2, p3) => {
            if (p1.trim() === '{' || p1.trim() === '}' || p1.trim() === '') {
                return match;
            }
            return p1 + '\n\n' + p2 + p3;
        });

        // Fix missing braces on single-line if
        // Matches: if (something) return something;
        // Does not match multiline yet.
        c = c.replace(/if\s*\(([^)]+)\)\s+(return[^;]+;)/g, "if () {\n  \n}");

        // LogViewer magic strings:
        if (f.endsWith('LogViewer.tsx')) {
            c = c.replace(/variant === "lane-a"/g, "variant === LaneVariant.LaneA");
            c = c.replace(/variant === "lane-b"/g, "variant === LaneVariant.LaneB");
            if (c.includes('LaneVariant.') && !c.includes('LaneVariant')) {
                c = "export enum LaneVariant { LaneA = 'lane-a', LaneB = 'lane-b' }\n" + c;
            }
        }

        // TraceViewer magic strings:
        if (f.endsWith('TraceViewer.tsx')) {
            c = c.replace(/type StatusFilter = "all" \| "traced" \| "drift" \| "orphan";/, "export enum StatusFilter { All = 'all', Traced = 'traced', Drift = 'drift', Orphan = 'orphan' }");
            
            c = c.replace(/"all"/g, "StatusFilter.All");
            c = c.replace(/"traced"/g, "StatusFilter.Traced");
            c = c.replace(/"drift"/g, "StatusFilter.Drift");
            c = c.replace(/"orphan"/g, "StatusFilter.Orphan");
            
            c = c.replace(/'all'/g, "StatusFilter.All");
            c = c.replace(/'traced'/g, "StatusFilter.Traced");
            c = c.replace(/'drift'/g, "StatusFilter.Drift");
            c = c.replace(/'orphan'/g, "StatusFilter.Orphan");
        }

        return c;
    });
}

