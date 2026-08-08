import { createContext, useContext } from 'react'

export type ThemeMode = 'light' | 'dark'

export interface ThemeContextType {
  theme: ThemeMode
  setTheme: (theme: ThemeMode) => Promise<void>
  toggleTheme: () => Promise<void>
  isThemeEnabled: boolean
}

export const ThemeContext = createContext<ThemeContextType | undefined>(undefined)

export function useTheme() {
  const context = useContext(ThemeContext)
  if (!context) {
    throw new Error('useTheme must be used within ThemeProvider')
  }
  return context
}
