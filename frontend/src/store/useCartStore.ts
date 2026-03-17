import { create } from 'zustand';

interface CartItem {
  id: number;
  name: string;
  price: number;
  quantity: number;
}

interface CartStore {
  items: CartItem[];
  addItem: (product: any) => void;
  removeItem: (id: number) => void; // This will decrement quantity
  deleteItem: (id: number) => void; // This will remove entirely
  clearCart: () => void;
  total: number;
}

const calculateTotal = (items: CartItem[]) => {
  return items.reduce((sum, item) => sum + (Number(item.price) * item.quantity), 0);
};

export const useCartStore = create<CartStore>((set, get) => ({
  items: [],
  total: 0,
  addItem: (product) => {
    const items = get().items;
    const existingItem = items.find((item) => item.id === product.id);
    let newItems;

    if (existingItem) {
      newItems = items.map((item) =>
        item.id === product.id ? { ...item, quantity: item.quantity + 1 } : item
      );
    } else {
      newItems = [...items, { ...product, price: Number(product.price), quantity: 1 }];
    }

    set({ 
      items: newItems,
      total: calculateTotal(newItems)
    });
  },
  removeItem: (id) => {
    const items = get().items;
    const item = items.find(i => i.id === id);
    if (!item) return;

    let newItems;
    if (item.quantity > 1) {
      newItems = items.map(i => i.id === id ? { ...i, quantity: i.quantity - 1 } : i);
    } else {
      newItems = items.filter(i => i.id !== id);
    }

    set({ 
      items: newItems,
      total: calculateTotal(newItems)
    });
  },
  deleteItem: (id) => {
    const newItems = get().items.filter((item) => item.id !== id);
    set({ 
      items: newItems,
      total: calculateTotal(newItems)
    });
  },
  clearCart: () => set({ items: [], total: 0 }),
}));
