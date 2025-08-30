import { CONFIG } from './config.js';

export class Cart{
  constructor(items=[]){ this.items = items; }

  static load(){
    try{
      const raw = localStorage.getItem('cart');
      const arr = raw? JSON.parse(raw) : [];
      return new Cart(Array.isArray(arr) ? arr : []);
    }catch{ return new Cart([]); }
  }
  save(){ localStorage.setItem('cart', JSON.stringify(this.items)); }

  addItem(product, qty=1){
    const idx = this.items.findIndex(i => i.id === product.id);
    if(idx>=0){
      this.items[idx].qty += qty;
    }else{
      this.items.push({ id: product.id, name: product.name, price: product.price, image: product.image||'', qty });
    }
    this.save(); return this;
  }
  updateQty(id, qty){
    const it = this.items.find(i => i.id === id);
    if(it){
      it.qty = Math.max(1, parseInt(qty,10)||1);
      this.save();
    }
    return this;
  }
  remove(id){
    this.items = this.items.filter(i => i.id !== id);
    this.save(); return this;
  }
  clear(){ this.items = []; this.save(); return this; }

  totalItems(){ return this.items.reduce((a,b)=>a + (b.qty||0), 0); }
  subtotal(){ return this.items.reduce((a,b)=>a + b.price * b.qty, 0); }
  tax(){ return Math.round(this.subtotal() * CONFIG.taxRate); }
  shipping(){
    const s = this.subtotal();
    return s >= CONFIG.freeShippingOver || s===0 ? 0 : CONFIG.shippingFlat;
  }
  total(){ return this.subtotal() + this.tax() + this.shipping(); }
}
