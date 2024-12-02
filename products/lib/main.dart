import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:products/screens/product_list_screen.dart';
import 'package:products/screens/cart_screen.dart';
import 'package:products/screens/checkout_screen.dart';
import 'package:products/view_models/product_view_model.dart';
import 'package:products/view_models/cart_view_model.dart';

void main() {
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MultiProvider(
      providers: [
        ChangeNotifierProvider(create: (_) => ProductViewModel()),
        ChangeNotifierProvider(create: (_) => CartViewModel()),
      ],
      child: MaterialApp(
        title: 'Online Order App',
        debugShowCheckedModeBanner: false,
        theme: ThemeData(
          primarySwatch: Colors.blue,
        ),
        initialRoute: '/',
        routes: {
          '/': (context) => const ProductListScreen(),
          '/cart': (context) => CartScreen(),
          '/checkout': (context) => const CheckoutScreen(),
        },
      ),
    );
  }
}
