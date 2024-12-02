import 'package:flutter/material.dart';
import 'package:products/models/product.dart';

class ProductCard extends StatelessWidget {
  final Product product;
  final VoidCallback onAddToCart;

  const ProductCard({super.key, required this.product, required this.onAddToCart});

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.symmetric(vertical: 10, horizontal: 15),
      child: ListTile(
        contentPadding: const EdgeInsets.all(10),
        dense: true,
        visualDensity: const VisualDensity(vertical: 4),
        leading: Image.network(product.thumbnail, width: 80, height: 80),
        title: Text(product.title),
        subtitle: Text(product.description),
        trailing: Column(
          crossAxisAlignment: CrossAxisAlignment.end,
          mainAxisAlignment: MainAxisAlignment.spaceEvenly,
          mainAxisSize: MainAxisSize.max,
          children: [

            IconButton(
              icon: const Icon(Icons.add_shopping_cart),
              onPressed: onAddToCart,
            ),
            Text('\$${product.price}'),
          ],
        ),
      ),
    );
  }
}
