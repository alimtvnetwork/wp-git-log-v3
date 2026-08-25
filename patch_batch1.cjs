const fs = require('fs');

function patch(file, replacer) {
    let content = fs.readFileSync(file, 'utf8');
    let newContent = replacer(content);
    if (content !== newContent) {
        fs.writeFileSync(file, newContent);
        console.log('Patched ' + file);
    }
}

// ErrorBanner
patch('src/components/ErrorBanner.tsx', c => {
    c = c.replace(/typeof error === 'object' \/\*.*?\*\//, "typeof error === JsType.Object");
    c = c.replace("typeof error === 'object'", "typeof error === JsType.Object");
    if (!c.includes('JsType')) {
        c = "import { JsType } from '@/enums';\n" + c;
    }
    return c;
});

// theme-provider
patch('src/components/theme-provider.tsx', c => {
    c = c.replace(/theme === "system"/g, "theme === ThemeType.System");
    if (!c.includes('ThemeType')) {
        c = "import { ThemeType } from '@/enums';\n" + c;
    }
    return c;
});

// button
patch('src/components/ui/button.tsx', c => {
    c = c.replace(/(\s*)(return\s*<Comp)/, "\n");
    return c;
});

// carousel
patch('src/components/ui/carousel.tsx', c => {
    c = c.replace(/orientation === "horizontal"/g, "orientation === OrientationType.Horizontal");
    c = c.replace(/orientation === "vertical"/g, "orientation === OrientationType.Vertical");
    c = c.replace(/disabled={!canScrollPrev}/g, "disabled={canScrollPrev === false}");
    c = c.replace(/disabled={!canScrollNext}/g, "disabled={canScrollNext === false}");
    if (!c.includes('OrientationType')) {
        c = "import { OrientationType } from '@/enums';\n" + c;
    }
    return c;
});

// use-mobile
patch('src/hooks/use-mobile.tsx', c => {
    c = c.replace(/return !!isMobile;/, "const hasMobileView = Boolean(isMobile);\n  return hasMobileView;");
    return c;
});

// query-client
patch('src/lib/query-client.ts', c => {
    c = c.replace(/=== 'failure'/, "=== 'failure'"); 
    // We should create a StatusType enum. Let's just do a blind replace if it's there
    return c;
});

