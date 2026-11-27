<?php

class Visit
{
    public int $visita_grupal_id;
    public string $nombre_completo;
    public int $folio_visita;
    public string $ine;
    public string $nss;
    public string $archivo_ine;
    public string $archivo_nss;
    public string $fecha_registro;

    public function __construct(
        int $visita_grupal_id,
        string $nombre_completo,
        int $folio_visita,
        string $ine,
        string $nss,
        string $archivo_ine,
        string $archivo_nss,
        string $fecha_registro
    ) {
        $this->visita_grupal_id = $visita_grupal_id;
        $this->nombre_completo = $nombre_completo;
        $this->folio_visita = $folio_visita;
        $this->ine = $ine;
        $this->nss = $nss;
        $this->archivo_ine = $archivo_ine;
        $this->archivo_nss = $archivo_nss;
        $this->fecha_registro = $fecha_registro;
    }

    public function toArray(): array
    {
        return [
            'visita_grupal_id' => $this->visita_grupal_id,
            'nombre_completo' => $this->nombre_completo,
            'folio_visita' => $this->folio_visita,
            'ine' => $this->ine,
            'nss' => $this->nss,
            'archivo_ine' => $this->archivo_ine,
            'archivo_nss' => $this->archivo_nss,
            'fecha_registro' => $this->fecha_registro
        ];
    }
}