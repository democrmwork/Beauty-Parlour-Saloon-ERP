import { useCallback, useEffect, useMemo, useState, type ReactNode } from 'react'
import { ThemeContext, type ThemeMode } from './ThemeContext'
import { useAuth } from './AuthContext'
import { useAppSettings } from './SettingsContext'
import { authService } from '@/services/authService'

const THEME_STORAGE_KEY = 'beauty_salon_theme'

interface ThemeProviderProps {
  children: ReactNode
}

export function ThemeProvider({ children }: ThemeProviderProps) {
  const { user, refreshUser, isAuthenticated } = useAuth()
  const { settings } = useAppSettings()

  const isThemeEnabled = settings?.enable_theme_mode !== false

  const [theme, setThemeState] = useState<ThemeMode>(() => {
    const stored = localStorage.getItem(THEME_STORAGE_KEY) as ThemeMode | null
    if (stored === 'dark' || stored === 'light') return stored
    return 'light'
  })

  // Synchronize with user's saved preference when logged in
  useEffect(() => {
    if (isAuthenticated && user?.theme && (user.theme === 'light' || user.theme === 'dark')) {
      setThemeState(user.theme)
      localStorage.setItem(THEME_STORAGE_KEY, user.theme)
    }
  }, [isAuthenticated, user?.theme])

  // Apply or remove theme class on document element
  useEffect(() => {
    const root = document.documentElement
    if (theme === 'dark' && isThemeEnabled) {
      root.classList.add('dark')
      root.setAttribute('data-theme', 'dark')
    } else {
      root.classList.remove('dark')
      root.setAttribute('data-theme', 'light')
    }
  }, [theme, isThemeEnabled])

  const setTheme = useCallback(
    async (nextTheme: ThemeMode) => {
      setThemeState(nextTheme)
      localStorage.setItem(THEME_STORAGE_KEY, nextTheme)

      if (isAuthenticated) {
        try {
          await authService.updateTheme(nextTheme)
          await refreshUser()
        } catch {
          // Fallback gracefully if network call fails
        }
      }
    },
    [isAuthenticated, refreshUser]
  )

  const toggleTheme = useCallback(async () => {
    const next = theme === 'light' ? 'dark' : 'light'
    await setTheme(next)
  }, [theme, setTheme])

  const value = useMemo(
    () => ({
      theme,
      setTheme,
      toggleTheme,
      isThemeEnabled,
    }),
    [theme, setTheme, toggleTheme, isThemeEnabled]
  )

  return <ThemeContext.Provider value={value}>{children}</ThemeContext.Provider>
}
