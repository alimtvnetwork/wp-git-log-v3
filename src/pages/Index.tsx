// Update this page (the content is just a fallback if you fail to update the page)

import { Link } from "react-router-dom";

// IMPORTANT: Fully REPLACE this with your own code
const PlaceholderIndex = () => {
  // PLACEHOLDER: Replace this entire return statement with the user's app.
  // The inline background color is intentionally not part of the design system.
  return (
    <div className="relative flex min-h-screen items-center justify-center" style={{ backgroundColor: '#fcfbf8' }}>
      <img data-lovable-blank-page-placeholder="REMOVE_THIS" src="/placeholder.svg" alt="Your app will live here!" />
      <Link
        to="/trace"
        className="absolute bottom-4 right-4 inline-flex items-center gap-1.5 rounded-md border border-border bg-card px-3 py-1.5 text-xs font-medium text-foreground shadow-sm hover:bg-accent transition-colors"
      >
        Open Trace Viewer →
      </Link>
    </div>
  );
};

const Index = PlaceholderIndex;

export default Index;
