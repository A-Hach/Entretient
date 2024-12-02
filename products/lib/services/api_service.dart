import 'dart:convert';
import 'package:flutter/foundation.dart';
import 'package:http/http.dart' as http;
import 'package:products/models/product.dart';
import 'package:products/constants.dart';

class ApiService {
  // Fetch products from the API
  Future<List<Product>> fetchProducts() async {
    try {
      final response = await http.get(Uri.parse(Constants.baseUrl));

      // Check if the API call was successful (status code 200)
      if (response.statusCode == 200) {
        final List<dynamic> data = json.decode(response.body)['products'];

        // Map the response to a list of Product objects
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
