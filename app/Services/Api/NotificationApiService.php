<?php

namespace App\Services\Api;

class NotificationApiService extends BaseApiService
{
    public function list(array $query = []): array
    {
        return $this->get('notifications.list', [], $query);
    }

    public function create(array $data): array
    {
        return $this->post('notifications.create', [], $data);
    }

    public function my(array $query = []): array
    {
        return $this->get('notifications.my', [], $query);
    }

    public function show(int $notificationId): array
    {
        return $this->get('notifications.show', ['notification_id' => $notificationId]);
    }

    public function markAsRead(int $notificationId): array
    {
        return $this->patch('notifications.read', ['notification_id' => $notificationId]);
    }

    public function markAllAsRead(): array
    {
        return $this->patch('notifications.read_all');
    }

    /**
     * Count unread notifications by fetching the list and filtering.
     */
    public function unreadCount(): int
    {
        $response = $this->my(['limit' => 100]);
        $items = $response['data'] ?? ($response['notifications'] ?? []);

        $count = 0;
        foreach ($items as $item) {
            if (empty($item['is_read']) && empty($item['read_at'])) {
                $count++;
            }
        }

        return $count;
    }
}
