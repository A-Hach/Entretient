import 'package:flutter/foundation.dart';
import 'package:products/services/api_service.dart';
import 'package:products/models/product.dart';

class ProductViewModel with ChangeNotifier {
  final ApiService _apiService = ApiService();
  List<Product> _products = [];
  bool _isLoading = false;

  List<Product> get products => _products;
  bool get isLoading => _isLoading;

  Future<void> fetchProducts() async {
    _isLoading = true;
    notifyListeners();
    try {
      _products = await _apiService.fetchProducts();
    } catch (e) {
      if (kDebugMode) {
        print('Error fetching products: $e');
      }
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }
}
