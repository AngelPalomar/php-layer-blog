<?php
declare(strict_types=1);

class HttpResponse
{
    private const SUCCESS_CODES = [200, 201, 202, 204];

    private int $code;
    private $data;
    private bool $success;
    private array $meta;
    private array $errors;
    private string $message;

    public function __construct(int $code, $data = [], string $message = '', array $meta = [], array $errors = [])
    {
        $this->code = $code;
        $this->data = $data;
        $this->message = $message;
        $this->errors = $errors;
        $this->success = in_array($code, self::SUCCESS_CODES, true);
        $this->meta = [
            'generatedAt' => date('Y-m-d H:i:s'),
            'custom' => $meta,
        ];
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function generate(): array
    {
        return [
            'success' => $this->success,
            'data' => $this->data,
            'message' => $this->message,
            'meta' => $this->meta,
            'errors' => $this->errors,
        ];
    }
}