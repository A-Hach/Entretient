import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import 'package:products/view_models/cart_view_model.dart';
import 'package:provider/provider.dart';

class CheckoutScreen extends StatelessWidget {
  const CheckoutScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final cartViewModel = Provider.of<CartViewModel>(context);

    return Scaffold(
      appBar: AppBar(
        title: const Text('Checkout'),
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text(
              'Order Summary',
              style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 10),
            ListView.builder(
              shrinkWrap: true,
              itemCount: cartViewModel.cartItems.length,
              itemBuilder: (context, index) {
                final cartItem = cartViewModel.cartItems[index];
                return ListTile(
                  leading: Image.network(cartItem.product.thumbnail),
                  title: Text(cartItem.product.title),
                  subtitle: Text('Quantity: ${cartItem.quantity}'),
                  trailing: Text('\$${cartItem.totalPrice}'),
                );
              },
            ),
            const SizedBox(height: 20),
            Text(
              'Total Price: \$${cartViewModel.totalPrice}',
              style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 20),
            ElevatedButton(
              onPressed: () async {
                if (kDebugMode) {
                  print('Order sent: ${cartViewModel.cartItems}');
                }
                showDialog(
                  context: context,
                  builder: (context) => AlertDialog(
                    title: const Text('Order Placed'),
                    content:
                        const Text('Your order has been placed successfully!'),
                    actions: [
                      TextButton(
                        onPressed: () {
                          Navigator.pop(context);
                          cartViewModel.cartItems.clear();
                          Navigator.pushNamedAndRemoveUntil(
                              context, '/', ModalRoute.withName("/"));
                        },
                        child: const Text('OK'),
                      ),
                    ],
                  ),
                );
              },
              child: const Text('Place Order'),
            ),
          ],
        ),
      ),
    );
  }
}
