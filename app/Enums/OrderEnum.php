<?php

namespace App\Enums;

enum OrderEnum:string {
  const PENDING = 'Pending';
  const PROCESSING = 'Processing';
  const CANCELLED = 'Cancelled';
  const SHIPPED = 'Shipped';
  const OUT_FOR_DELIVERY = 'Out For Delivery';
  const DELIVERED = 'Delivered';
}
