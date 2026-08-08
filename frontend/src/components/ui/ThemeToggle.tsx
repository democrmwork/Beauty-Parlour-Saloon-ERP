import { Moon, Sun } from 'lucide-react'
import { useTheme } from '@/contexts/ThemeContext'
import { cn } from '@/utils/cn'

interface ThemeToggleProps {
  className?: string
  variant?: 'button' | 'icon'
}

export function ThemeToggle({ className, variant = 'button' }: ThemeToggleProps) {
  const { theme, toggleTheme, isThemeEnabled } = useTheme()

  if (!isThemeEnabled) return null

  const isDark = theme === 'dark'

  if (variant === 'icon') {
    return (
      <button
        type="button"
        onClick={toggleTheme}
        className={cn(
          'relative flex h-9 w-9 items-center justify-center rounded-xl border border-border bg-card text-foreground transition-all hover:bg-muted focus:outline-none',
          className
        )}
        title={isDark ? 'Switch to Day Mode' : 'Switch to Night Mode'}
        aria-label={isDark ? 'Switch to Day Mode' : 'Switch to Night Mode'}
      >
        {isDark ? (
          <Sun className="h-4 w-4 text-amber-400 transition-transform duration-200 hover:rotate-45" />
        ) : (
          <Moon className="h-4 w-4 text-slate-700 transition-transform duration-200 hover:-rotate-12 dark:text-amber-300" />
        )}
      </button>
    )
  }

  return (
    <button
      type="button"
      onClick={toggleTheme}
      className={cn(
        'inline-flex items-center gap-2 rounded-xl border border-border bg-muted/60 px-3 py-1.5 text-xs font-medium text-foreground transition-colors hover:bg-muted',
        className
      )}
      title={isDark ? 'Switch to Day Mode' : 'Switch to Night Mode'}
      aria-label={isDark ? 'Switch to Day Mode' : 'Switch to Night Mode'}
    >
      {isDark ? (
        <>
          <Sun className="h-4 w-4 text-amber-400" />
          <span>Day Mode</span>
        </>
      ) : (
        <>
          <Moon className="h-4 w-4 text-slate-700 dark:text-amber-300" />
          <span>Night Mode</span>
        </>
      )}
    </button>
  )
}
