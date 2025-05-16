<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notification_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Get order notifications for a specific user
     * 
     * @param int $user_id User ID
     * @param int $offset Offset for pagination
     * @param int $limit Limit for pagination
     * @return array Array of order notifications
     */
    public function get_user_notifications($user_id, $offset = 0, $limit = 10) {
        // Using a custom SQL query to handle the complex ordering
        $sql = "SELECT id_order, id_user, stts_pemesanan, tgl_pemesanan, tgl_dikirim, tgl_selesai, tgl_batal 
                FROM orders 
                WHERE id_user = ? 
                ORDER BY 
                    CASE 
                        WHEN tgl_selesai IS NOT NULL THEN tgl_selesai
                        WHEN tgl_dikirim IS NOT NULL THEN tgl_dikirim
                        WHEN tgl_batal IS NOT NULL THEN tgl_batal
                        ELSE tgl_pemesanan
                    END DESC
                LIMIT ?, ?";
        
        $query = $this->db->query($sql, array($user_id, (int)$offset, (int)$limit));
        return $query->result_array();
    }
    
    /**
     * Count unread notifications for a user
     * 
     * @param int $user_id User ID
     * @return int Number of unread notifications
     */
    public function count_unread($user_id) {
        // Since we don't have a read/unread status in orders table,
        // we'll consider orders with status changes in the last 7 days as "unread"
        $this->db->where('id_user', $user_id);
        $this->db->where('(
            (stts_pemesanan = "diproses" AND tgl_pemesanan >= DATE_SUB(NOW(), INTERVAL 7 DAY)) OR
            (stts_pemesanan = "dikirim" AND tgl_dikirim >= DATE_SUB(NOW(), INTERVAL 7 DAY)) OR
            (stts_pemesanan = "selesai" AND tgl_selesai >= DATE_SUB(NOW(), INTERVAL 7 DAY)) OR
            (stts_pemesanan = "dibatalkan" AND tgl_batal >= DATE_SUB(NOW(), INTERVAL 7 DAY))
        )');
        
        return $this->db->count_all_results('orders');
    }
    
    /**
     * Mark all notifications as read for a user
     * 
     * @param int $user_id User ID
     * @return bool Success status
     */
    public function mark_all_as_read($user_id) {
        // Since we don't have a read/unread status in orders table,
        // we'll just return true as if they were marked as read
        return true;
    }
}