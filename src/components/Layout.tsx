import React from 'react';
import { Outlet, Link, useLocation } from 'react-router-dom';
import { Activity, Book, GitMerge, Settings, Shield } from 'lucide-react';
import {
  Sidebar,
  SidebarContent,
  SidebarGroup,
  SidebarGroupContent,
  SidebarGroupLabel,
  SidebarMenu,
  SidebarMenuButton,
  SidebarMenuItem,
  SidebarProvider,
} from '@/components/ui/sidebar';

const navItems = [
  { name: 'Dashboard', path: '/', icon: Activity },
  { name: 'Repositories', path: '/repos', icon: Book },
  { name: 'Pipelines', path: '/pipelines', icon: GitMerge },
  { name: 'Profiles', path: '/profiles', icon: Shield },
  { name: 'Settings', path: '/settings', icon: Settings },
];

export const Layout: React.FC = () => {
  const location = useLocation();

  return (
    <SidebarProvider>
      <div className="flex min-h-screen w-full bg-background">
        <Sidebar className="border-r">
          <SidebarContent>
            <div className="p-4 flex items-center gap-2 border-b">
              <GitMerge className="h-6 w-6 text-primary" />
              <span className="font-bold text-lg tracking-tight">Git Logs v2</span>
            </div>
            
            <SidebarGroup>
              <SidebarGroupLabel>Navigation</SidebarGroupLabel>
              <SidebarGroupContent>
                <SidebarMenu>
                  {navItems.map((item) => {
                    const isActive = location.pathname === item.path || 
                                    (item.path !== '/' && location.pathname.startsWith(item.path));
                    
                    return (
                      <SidebarMenuItem key={item.path}>
                        <SidebarMenuButton asChild isActive={isActive} tooltip={item.name}>
                          <Link to={item.path}>
                            <item.icon className="h-4 w-4" />
                            <span>{item.name}</span>
                          </Link>
                        </SidebarMenuButton>
                      </SidebarMenuItem>
                    );
                  })}
                </SidebarMenu>
              </SidebarGroupContent>
            </SidebarGroup>
          </SidebarContent>
        </Sidebar>

        <main className="flex-1 overflow-auto">
          <div className="p-8">
            <Outlet />
          </div>
        </main>
      </div>
    </SidebarProvider>
  );
};
