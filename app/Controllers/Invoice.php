<?php

namespace App\Controllers;

use App\Models\DiscountModel;
use App\Models\ProductModel;

class Invoice extends BaseController
{
  public function index()
  {
    $productModel = new ProductModel();

    if ($this->request->getMethod() == 'post') {
      $rules = $this->validationRules();
      if ($this->validate($rules)) {
        $postArray = $this->request->getPost();

        if (isset($postArray['id'])) {
          $productData['id'] = $postArray['id'];
        }

        $productData['name']        = $postArray['name'];
        $productData['quantity']    = $postArray['quantity'];
        $productData['price']       = $postArray['price'];
        $productData['tax']         = $postArray['tax'];
        $productData['total_price'] = $productData['price'] * $productData['quantity'];

        $taxAmount = $productData['total_price'] * $productData['tax'] / 100;
        $productData['total_amount'] = $productData['total_price'] + $taxAmount;

        $productModel->save($productData);

        $this->setMessage('Data updated successfully', 'success');
        return redirect()->to('/');
      } else {
        $data['validation'] = $this->validator;
      }
    }

    if ($this->request->getGet('id')) {
      $productDetails = $productModel->find($this->request->getGet('id'));
      if ($productDetails) {
        $data['productDetails'] = $productDetails;
      } else {
        $this->setMessage('Unknown product choosed', 'danger');
        return redirect()->to('/');
      }
    }

    $data['products'] = $productModel->paginate(10);
    $data['pager']    = $productModel->pager;
    $data['alert']    = session()->getFlashdata();
    $data['sumData']  = $productModel->getSumData();

    $discountModel = new DiscountModel();
    $data['discountDetails'] = $discountModel->first();

    return view('invoice', $data);
  }

  public function deleteProduct($id)
  {
    if (!empty($id)) {
      $productModel = new ProductModel();
      $products = $productModel->find($id);
      if (!empty($products)) {
        $productModel->delete($id);
        $this->setMessage('Product deleted successfully', 'success');
      } else {
        $this->setMessage('Unknown product choosed', 'danger');
      }
    } else {
      $this->setMessage('Invalid request', 'danger');
    }

    return redirect()->to('/');
  }

  public function submitDiscount()
  {
    $postArray = $this->request->getPost();

    if (!empty($postArray['discountType']) && !empty($postArray['discountAmount'])) {
      if ($postArray['discountAmount'] > 0) {
        if ($postArray['discountType'] == 'percentage' && $postArray['discountAmount'] > 100) {
          $response['code'] = 400;
        } else {
          $productModel = new ProductModel();
          $sumData      = $productModel->getSumData();

          $totalAmount = $sumData['total_amount'];
          if ($postArray['discountType'] === 'percentage') {
            $totalAmount -= ($sumData['total_amount'] * $postArray['discountAmount'] / 100);
          } else {
            $totalAmount -= $postArray['discountAmount'];
          }

          $discountModel   = new DiscountModel();
          $discountDetails = $discountModel->first();
          if ($discountDetails) {
            $discountData['id'] = $discountDetails['id'];
          }

          $discountData['type']    = $postArray['discountType'];
          $discountData['amount']  = $postArray['discountAmount'];
          $discountData['discounted_total'] = $totalAmount;

          $discountModel->save($discountData);

          $response['code'] = 200;
        }
      } else {
        $response['code'] = 400;
      }
    } else {
      $response['code'] = 400;
    }

    echo json_encode($response);
  }

  public function generateInvoice()
  {
    $productModel = new ProductModel();
    $products     = $productModel->findAll();
    if (empty($products)) {
      $this->setMessage('No products added', 'info');
      return redirect()->to('/');
    }

    $data['products'] = $products;
    $data['sumData']  = $productModel->getSumData();

    $discountModel = new DiscountModel();
    $data['discountDetails'] = $discountModel->first();

    return view('generate-invoice', $data);
  }

  private function setMessage($message, $class)
  {
    session()->setFlashdata('message', $message);
    session()->setFlashdata('class', $class);
  }

  private function validationRules()
  {
    $rules['name'] = [
      'label' => 'name',
      'rules' => 'required|min_length[3]',
    ];

    $rules['quantity'] = [
      'label' => 'quantity',
      'rules' => 'required|numeric|greater_than[0]',
    ];

    $rules['price'] = [
      'label' => 'price',
      'rules' => 'required|numeric|greater_than[0]',
    ];

    $rules['tax'] = [
      'label' => 'tax',
      'rules' => 'required',
    ];

    return $rules;
  }
}
