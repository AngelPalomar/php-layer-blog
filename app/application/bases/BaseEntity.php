<?php declare(strict_types=1);

use DateTime;

abstract class BaseEntity
{
  public int $id = 0;
  public bool $estaActivo = false;
  public int $creadoPor = 0;
  public int $modificadoPor = 0;
  public ?DateTime $fechaCreacion = null;
  public ?DateTime $fechaModificado = null;

  public function __construct(array $SQL)
  {
    $this->id = (int) $SQL['id'] ?? 0;
    $this->estaActivo = (bool) ($SQL['estaActivo'] ?? false);
    $this->creadoPor = (int) ($SQL['creadoPor'] ?? 0);
    $this->modificadoPor = (int) ($SQL['modificadoPor'] ?? 0);

    $this->fechaCreacion = $this->parseDate($SQL['fechaCreacion'] ?? null);
    $this->fechaModificado = $this->parseDate($SQL['fechaModificado'] ?? null);
  }

  private function parseDate(?string $sqlDate): ?DateTime
  {
    if (empty($sqlDate)) {
      return null;
    }

    $date = DateTime::createFromFormat('Y-m-d H:i:s', $sqlDate);
    return $date !== false ? $date : null;
  }

  protected function toArray(): array
  {
    return [
      'id' => $this->id,
      'estaActivo' => $this->estaActivo,
      'creadoPor' => $this->creadoPor,
      'modificadoPor' => $this->modificadoPor,
      'fechaCreacion' => $this->fechaCreacion?->format('Y-m-d H:i:s'),
      'fechaModificado' => $this->fechaModificado?->format('Y-m-d H:i:s'),
    ];
  }
}
