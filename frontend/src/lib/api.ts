import axios from 'axios';

const api = axios.create({
  baseURL: process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

export const getProducts = () => api.get('/products');
export const createTransaction = (data: any) => api.post('/transactions', data);
export const getDashboardStats = () => api.get('/dashboard-stats');

export default api;
