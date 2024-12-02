import 'package:flutter/material.dart';
import 'package:products/view_models/cart_view_model.dart';
import 'package:provider/provider.dart';

import 'checkout_screen.dart';

class CartScreen extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    final cartViewModel = Provider.of<CartViewModel>(context);

    return Scaffold(
      appBar: AppBar(
        title: const Text('Your Cart'),
      ),
      body: cartViewModel.cartItems.isEmpty
          ? const Center(child: Text('No items in the cart'))
          : ListView.builder(
        itemCount: cartViewModel.cartItems.length,
        itemBuilder: (context, index) {
          final cartItem = cartViewModel.cartItems[index];
          return ListTile(
            leading: Image.network(cartItem.product.thumbnail, width: 50, height: 50),
            title: Text(cartItem.product.title),
            subtitle: Row(
              children: [
                const Text('Quantity: '),
                IconButton(
                  icon: const Icon(Icons.remove),
                  onPressed: () {
                    cartViewModel.updateQuantity(cartItem, cartItem.quantity - 1);
                  },
                ),
                Text(cartItem.quantity.toString()),
                IconButton(
                  icon: const Icon(Icons.add),
                  onPressed: () {
                    cartViewModel.updateQuantity(cartItem, cartItem.quantity + 1);
                  },
                ),
              ],
            ),
            trailing: Text('\$${cartItem.totalPrice}'),
            onLongPress: () {
              cartViewModel.removeFromCart(cartItem);
            },
          );
        },
      ),
      bottomNavigationBar: cartViewModel.cartItems.isEmpty
          ? null
          : Padding(
        padding: const EdgeInsets.all(16.0),
        child: ElevatedButton(
          onPressed: () {
            Navigator.push(
              context,
              MaterialPageRoute(builder: (context) => const CheckoutScreen()),
            );
          },
          style: ElevatedButton.styleFrom(
            primary: Colors.blue, // Button color
            padding: const EdgeInsets.symmetric(vertical: 16.0),
            textStyle: const TextStyle(fontSize: 18),
          ),
          child: const Text('Checkout'),
        ),
      ),
    );
  }
}
