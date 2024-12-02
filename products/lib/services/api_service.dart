import 'dart:convert';
import 'package:flutter/foundation.dart';
import 'package:http/http.dart' as http;
import 'package:products/models/product.dart';
import 'package:products/constants.dart';

class ApiService {
<<<<<<< HEAD
=======

>>>>>>> f3621ee0d0760a70a25265bb5b9b2d10f7198bf4
  Future<List<Product>> fetchProducts() async {
    try {
      final response = await http.get(Uri.parse(Constants.baseUrl));

<<<<<<< HEAD
      if (response.statusCode == 200) {
        final List<dynamic> data = json.decode(response.body)['products'];


=======
     
      if (response.statusCode == 200) {
        final List<dynamic> data = json.decode(response.body)['products'];

        
>>>>>>> f3621ee0d0760a70a25265bb5b9b2d10f7198bf4
        return data.map((product) => Product.fromJson(product)).toList();
      } else {
        throw Exception('Failed to load products');
      }
    } catch (error) {
      if (kDebugMode) {
        print("Error fetching products: $error");
      }
      rethrow;
    }
  }
}
