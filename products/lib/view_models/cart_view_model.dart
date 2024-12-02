import 'package:flutter/material.dart';
import 'package:products/models/cart_item.dart';
import 'package:products/models/product.dart';

class CartViewModel extends ChangeNotifier {
  final List<CartItem> _cartItems = [];

  List<CartItem> get cartItems => _cartItems;

  double get totalPrice {
    return _cartItems.fold(0, (total, item) => total + item.totalPrice);
  }

  void addToCart(Product product) {
    final existingItem = _cartItems.firstWhere(
          (cartItem) => cartItem.product.id == product.id,
      orElse: () => CartItem(product: product, quantity: 0),
    );

    if (existingItem.quantity > 0) {
      existingItem.quantity++;
    } else {
      _cartItems.add(CartItem(product: product, quantity: 1));
    }

    notifyListeners();
  }

  void updateQuantity(CartItem cartItem, int newQuantity) {
    cartItem.quantity = newQuantity;
    if (newQuantity <= 0) {
      removeFromCart(cartItem);
    }
    notifyListeners();
  }

  void removeFromCart(CartItem cartItem) {
    _cartItems.remove(cartItem);
    notifyListeners();
  }
}
