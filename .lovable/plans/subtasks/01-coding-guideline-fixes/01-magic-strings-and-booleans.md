# Subtask: 01-magic-strings-and-booleans

## Instructions
Fix the following coding guideline violations. Do NOT guess. Verify each file exists before modifying. Follow the "Minimum correct fix" rule.

### File: src\components\ErrorBanner.tsx
- [ ] Step 1: (Line 11) [missing_braces_on_if] Wrap if statement body in braces.
  - Match: if (!error) return null;
- [ ] Step 2: (Line 14) [magic_string] Extract magic string to a named Enum or constant.
  - Match: if (error && typeof error === 'object' && 'Status' in error) {

### File: src\components\theme-provider.tsx
- [ ] Step 3: (Line 38) [magic_string] Extract magic string to a named Enum or constant.
  - Match: if (theme === "system") {

### File: src\components\ui\button.tsx
- [ ] Step 4: (Line 42) [missing_blank_line_before_return] Add a blank line before return statement.
  - Match: return <Comp className={cn(buttonVariants({ variant, size, className }))} ref={ref} {...props} />;

### File: src\components\ui\carousel.tsx
- [ ] Step 5: (Line 46) [magic_string] Extract magic string to a named Enum or constant.
  - Match: axis: orientation === "horizontal" ? "x" : "y",
- [ ] Step 6: (Line 72) [magic_string] Extract magic string to a named Enum or constant.
  - Match: if (event.key === "ArrowLeft") {
- [ ] Step 7: (Line 75) [magic_string] Extract magic string to a named Enum or constant.
  - Match: } else if (event.key === "ArrowRight") {
- [ ] Step 8: (Line 111) [magic_string] Extract magic string to a named Enum or constant.
  - Match: orientation: orientation || (opts?.axis === "y" ? "vertical" : "horizontal"),
- [ ] Step 9: (Line 142) [magic_string] Extract magic string to a named Enum or constant.
  - Match: className={cn("flex", orientation === "horizontal" ? "-ml-4" : "-mt-4 flex-col", className)}
- [ ] Step 10: (Line 160) [magic_string] Extract magic string to a named Enum or constant.
  - Match: className={cn("min-w-0 shrink-0 grow-0 basis-full", orientation === "horizontal" ? "pl-4" : "pt-4", className)}
- [ ] Step 11: (Line 179) [magic_string] Extract magic string to a named Enum or constant.
  - Match: orientation === "horizontal"
- [ ] Step 12: (Line 184) [inverted_boolean] Extract into a positively named boolean and invert at declaration.
  - Match: disabled={!canScrollPrev}
- [ ] Step 13: (Line 207) [magic_string] Extract magic string to a named Enum or constant.
  - Match: orientation === "horizontal"
- [ ] Step 14: (Line 212) [inverted_boolean] Extract into a positively named boolean and invert at declaration.
  - Match: disabled={!canScrollNext}

### File: src\components\ui\chart.tsx
- [ ] Step 15: (Line 78) [missing_blank_line_before_return] Add a blank line before return statement.
  - Match: return color ? `  --color-${key}: ${color};` : null;
- [ ] Step 16: (Line 132) [magic_string] Extract magic string to a named Enum or constant.
  - Match: !labelKey && typeof label === "string"
- [ ] Step 17: (Line 151) [magic_string] Extract magic string to a named Enum or constant.
  - Match: const nestLabel = payload.length === 1 && indicator !== "dot";
- [ ] Step 18: (Line 173) [magic_string] Extract magic string to a named Enum or constant.
  - Match: indicator === "dot" && "items-center",
- [ ] Step 19: (Line 186) [magic_string] Extract magic string to a named Enum or constant.
  - Match: "h-2.5 w-2.5": indicator === "dot",
- [ ] Step 20: (Line 187) [magic_string] Extract magic string to a named Enum or constant.
  - Match: "w-1": indicator === "line",
- [ ] Step 21: (Line 188) [magic_string] Extract magic string to a named Enum or constant.
  - Match: "w-0 border-[1.5px] border-dashed bg-transparent": indicator === "dashed",
- [ ] Step 22: (Line 189) [magic_string] Extract magic string to a named Enum or constant.
  - Match: "my-0.5": nestLabel && indicator === "dashed",
- [ ] Step 23: (Line 247) [magic_string] Extract magic string to a named Enum or constant.
  - Match: className={cn("flex items-center justify-center gap-4", verticalAlign === "top" ? "pb-3" : "pt-3", className)}
- [ ] Step 24: (Line 279) [magic_string] Extract magic string to a named Enum or constant.
  - Match: if (typeof payload !== "object" || payload === null) {
- [ ] Step 25: (Line 284) [magic_string] Extract magic string to a named Enum or constant.
  - Match: "payload" in payload && typeof payload.payload === "object" && payload.payload !== null
- [ ] Step 26: (Line 290) [magic_string] Extract magic string to a named Enum or constant.
  - Match: if (key in payload && typeof payload[key as keyof typeof payload] === "string") {
- [ ] Step 27: (Line 295) [magic_string] Extract magic string to a named Enum or constant.
  - Match: typeof payloadPayload[key as keyof typeof payloadPayload] === "string"

### File: src\components\ui\scroll-area.tsx
- [ ] Step 28: (Line 27) [magic_string] Extract magic string to a named Enum or constant.
  - Match: orientation === "vertical" && "h-full w-2.5 border-l border-l-transparent p-[1px]",
- [ ] Step 29: (Line 28) [magic_string] Extract magic string to a named Enum or constant.
  - Match: orientation === "horizontal" && "h-2.5 flex-col border-t border-t-transparent p-[1px]",

### File: src\components\ui\select.tsx
- [ ] Step 30: (Line 70) [magic_string] Extract magic string to a named Enum or constant.
  - Match: position === "popper" &&
- [ ] Step 31: (Line 81) [magic_string] Extract magic string to a named Enum or constant.
  - Match: position === "popper" &&

### File: src\components\ui\separator.tsx
- [ ] Step 32: (Line 14) [magic_string] Extract magic string to a named Enum or constant.
  - Match: className={cn("shrink-0 bg-border", orientation === "horizontal" ? "h-[1px] w-full" : "h-full w-[1px]", className)}

### File: src\components\ui\sidebar.tsx
- [ ] Step 33: (Line 60) [magic_string] Extract magic string to a named Enum or constant.
  - Match: const openState = typeof value === "function" ? value(open) : value;
- [ ] Step 34: (Line 141) [magic_string] Extract magic string to a named Enum or constant.
  - Match: if (collapsible === "none") {
- [ ] Step 35: (Line 188) [magic_string] Extract magic string to a named Enum or constant.
  - Match: variant === "floating" || variant === "inset"
- [ ] Step 36: (Line 196) [magic_string] Extract magic string to a named Enum or constant.
  - Match: side === "left"
- [ ] Step 37: (Line 200) [magic_string] Extract magic string to a named Enum or constant.
  - Match: variant === "floating" || variant === "inset"
- [ ] Step 38: (Line 462) [magic_string] Extract magic string to a named Enum or constant.
  - Match: if (typeof tooltip === "string") {
- [ ] Step 39: (Line 471) [magic_string] Extract magic string to a named Enum or constant.
  - Match: <TooltipContent side="right" align="center" hidden={state !== "collapsed" || isMobile} {...tooltip} />
- [ ] Step 40: (Line 601) [magic_string] Extract magic string to a named Enum or constant.
  - Match: size === "sm" && "text-xs",
- [ ] Step 41: (Line 602) [magic_string] Extract magic string to a named Enum or constant.
  - Match: size === "md" && "text-sm",

### File: src\hooks\use-mobile.tsx
- [ ] Step 42: (Line 18) [inverted_boolean] Extract into a positively named boolean and invert at declaration.
  - Match: return !!isMobile;

### File: src\hooks\use-toast.ts
- [ ] Step 43: (Line 26) [missing_blank_line_before_return] Add a blank line before return statement.
  - Match: return count.toString();
- [ ] Step 44: (Line 74) [missing_blank_line_before_return] Add a blank line before return statement.
  - Match: return {
- [ ] Step 45: (Line 80) [missing_blank_line_before_return] Add a blank line before return statement.
  - Match: return {

### File: src\lib\query-client.ts
- [ ] Step 46: (Line 9) [magic_string] Extract magic string to a named Enum or constant.
  - Match: if (error.name === 'ApiError') {

