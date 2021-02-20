<?php

namespace App\Entity;

use App\Repository\IncidenciaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=IncidenciaRepository::class)
 */
class Incidencia
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titulo;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha_creacion;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $estado;

    /**
     * @ORM\ManyToOne(targetEntity=Usuario::class, inversedBy="incidencias")
     * @ORM\JoinColumn(nullable=false)
     */
    private $usuario;

    /**
     * @ORM\ManyToOne(targetEntity=Cliente::class, inversedBy="incidencias")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cliente;

    /**
     * @ORM\OneToMany(targetEntity=LineasIncidencia::class, mappedBy="incidencia", orphanRemoval=true)
     */
    private $lineasIncidencias;

    public function __construct()
    {
        $this->lineasIncidencias = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getFechaCreacion(): ?\DateTimeInterface
    {
        return $this->fecha_creacion;
    }

    public function setFechaCreacion(\DateTimeInterface $fecha_creacion): self
    {
        $this->fecha_creacion = $fecha_creacion;

        return $this;
    }

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): self
    {
        $this->estado = $estado;

        return $this;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getCliente(): ?Cliente
    {
        return $this->cliente;
    }

    public function setCliente(?Cliente $cliente): self
    {
        $this->cliente = $cliente;

        return $this;
    }

    /**
     * @return Collection|LineasIncidencia[]
     */
    public function getLineasIncidencias(): Collection
    {
        return $this->lineasIncidencias;
    }

    public function addLineasIncidencia(LineasIncidencia $lineasIncidencia): self
    {
        if (!$this->lineasIncidencias->contains($lineasIncidencia)) {
            $this->lineasIncidencias[] = $lineasIncidencia;
            $lineasIncidencia->setIncidencia($this);
        }

        return $this;
    }

    public function removeLineasIncidencia(LineasIncidencia $lineasIncidencia): self
    {
        if ($this->lineasIncidencias->removeElement($lineasIncidencia)) {
            // set the owning side to null (unless already changed)
            if ($lineasIncidencia->getIncidencia() === $this) {
                $lineasIncidencia->setIncidencia(null);
            }
        }

        return $this;
    }
}
