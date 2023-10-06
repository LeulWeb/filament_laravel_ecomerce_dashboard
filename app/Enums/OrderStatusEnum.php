<?php


enum OrderStatusEnum : string{
    case PENDING= 'pending';
    case DECLINED= 'declined';
    case PROCESSING = 'processing';
    case COMPLETED = 'completed';
}