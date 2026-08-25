const fs = require('fs');

function patch(file, replacer) {
    let content = fs.readFileSync(file, 'utf8');
    let newContent = replacer(content);
    if (content !== newContent) {
        fs.writeFileSync(file, newContent);
        console.log('Patched ' + file);
    }
}

const files = [
    'src/components/ui/chart.tsx',
    'src/components/ui/scroll-area.tsx',
    'src/components/ui/select.tsx',
    'src/components/ui/separator.tsx',
    'src/components/ui/sidebar.tsx',
    'src/hooks/use-toast.ts',
    'src/lib/query-client.ts',
    'src/pages/Dashboard.tsx'
];

for (let f of files) {
    if (!fs.existsSync(f)) continue;
    patch(f, c => {
        // magic strings to Enums
        c = c.replace(/orientation === "vertical"/g, "orientation === OrientationType.Vertical");
        c = c.replace(/orientation === "horizontal"/g, "orientation === OrientationType.Horizontal");
        c = c.replace(/=== 'left'/g, "=== DirectionType.Left");
        c = c.replace(/=== 'right'/g, "=== DirectionType.Right");
        
        // Blank lines before returns
        c = c.replace(/([^\n])\n(\s*)(return\s+)/g, (match, p1, p2, p3) => {
            if (p1.trim() === '{' || p1.trim() === '}' || p1.trim() === '') {
                return match;
            }
            return p1 + '\n\n' + p2 + p3;
        });

        if ((c.includes('OrientationType.') || c.includes('DirectionType.')) && !c.includes('@/enums')) {
            c = "import { OrientationType, DirectionType } from '@/enums';\n" + c;
        }
        
        return c;
    });
}
