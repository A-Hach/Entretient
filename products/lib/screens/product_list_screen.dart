import 'package:flutter/material.dart';
import 'package:products/view_models/product_view_model.dart';
import 'package:products/view_models/cart_view_model.dart';
import 'package:products/widgets/product_card.dart';
import 'package:provider/provider.dart';

class ProductListScreen extends StatelessWidget {
  const ProductListScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final productViewModel = Provider.of<ProductViewModel>(context);
    final cartViewModel = Provider.of<CartViewModel>(context);

    if (productViewModel.products.isEmpty) {
      productViewModel.fetchProducts();
    }

    return Scaffold(
      appBar: AppBar(
        title: const Text('Products'),
        actions: [
          IconButton(
            icon: const Icon(Icons.shopping_cart),
            onPressed: () {
              Navigator.pushNamed(context, '/cart');
            },
          ),
        ],
      ),
      body: productViewModel.products.isEmpty
          ? const Center(child: CircularProgressIndicator())
          : ListView.builder(
        itemCount: productViewModel.products.length,
        itemBuilder: (context, index) {
          final product = productViewModel.products[index];
          return ProductCard(product: product, onAddToCart: () {
            cartViewModel.addToCart(product);
          });
        },
      ),
    );
  }
}
