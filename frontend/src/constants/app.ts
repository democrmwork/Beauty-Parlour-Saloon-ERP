function getApiBaseUrl(): string {
  const envUrl = (import.meta.env.VITE_API_BASE_URL || '').trim()
  if (envUrl) {
    let url = envUrl
    if (url.startsWith('http:/') && !url.startsWith('http://')) {
      url = url.replace('http:/', 'http://')
    }
    url = url.replace(/\/+$/, '')
    if (!url.endsWith('/api/v1')) {
      url += '/api/v1'
    }
    return url
  }

  // Development uses Vite proxy (/api/v1 -> localhost:8000). Production defaults to full Vercel backend URL.
  if (import.meta.env.DEV) {
    return '/api/v1'
  }

  return 'https://saloonerp-api.vercel.app/api/v1'
}

export const APP_NAME = import.meta.env.VITE_APP_NAME || 'Beauty Salon ERP'
export const API_BASE_URL = getApiBaseUrl()
export const TOKEN_KEY = 'beauty_salon_auth_token'
export const USER_KEY = 'beauty_salon_user'

export const CURRENCY = 'AED'
export const CURRENCY_SYMBOL = 'د.إ'
export const VAT_RATE = 5
export const TIMEZONE = 'Asia/Dubai'
