import 'package:flutter/material.dart';
import 'package:products/models/cart_item.dart';
import 'package:products/view_models/cart_view_model.dart';
import 'package:provider/provider.dart';

class CartItemCard extends StatelessWidget {
  final CartItem cartItem;

  const CartItemCard({super.key, required this.cartItem});

  @override
  Widget build(BuildContext context) {
    return ListTile(
      leading: Image.network(cartItem.product.thumbnail),
      title: Text(cartItem.product.title),
      subtitle: Text('Quantity: ${cartItem.quantity} - \$${cartItem.totalPrice}'),
      trailing: IconButton(
        icon: const Icon(Icons.remove_circle),
        onPressed: () {
          final cartViewModel = Provider.of<CartViewModel>(context, listen: false);
          cartViewModel.removeFromCart(cartItem);
        },
      ),
    );
  }
}
