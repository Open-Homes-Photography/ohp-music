import { http } from '@/services/http'

export const keywordStore = {
  fetchAll: async () => await http.get<Keyword[]>('keywords'),
  fetchOne: async (name: string) => await http.get<Keyword>(`keywords/${name}`),
}
