<?php

namespace App\Models\PostgreSQL;

class PersonModel
{
	private $idPerson;
	private $name;
	private $birth;
	private $gender;
	private $age;
	private $city;
	private $estate;
	private $timeDiagnosis;

    public function getIdPerson(): int {
		return $this->idPerson;
	}

	/**
     * @return self
     */

	public function setIdPerson(int $idPerson): self {
		$this->idPerson = $idPerson;
        return $this;
	}

	public function getName(): string {
		return $this->name;
	}

	/**
     * @return self
     */

	public function setName(string $name): self{
		$this->name = $name;
        return $this;
	}

	public function getBirth(): string {
		return $this->birth;
	}

	/**
     * @return self
     */

	public function setBirth(string $birth): self {
		$this->birth = $birth;
        return $this;
	}
	
	public function getGender(): string {
		return $this->gender;
	}

	/**
     * @return self
     */

	public function setGender(string $gender): self {
		$this->gender = $gender;
        return $this;
	}

	public function getAge(): int {
		return $this->age;
	}

	/**
     * @return self
     */

	public function setAge(string $age) : self {
		$this->age = $age;
        return $this;
	}

	public function getCity(): string {
		return $this->city;
	}

	/**
     * @return self
     */

	public function setCity(string $city) : self {
		$this->city = $city;
        return $this;
	}

	public function getEstate(): string {
		return $this->estate;
	}

	/**
     * @return self
     */

	public function setEstate(string $estate) : self {
		$this->estate = $estate;
        return $this;
	}

	public function getTimeDiagnosis(): string {
		return $this->timeDiagnosis;
	}

	/**
     * @return self
     */

	public function setTimeDiagnosis(string $timeDiagnosis) : self {
		$this->timeDiagnosis = $timeDiagnosis;
        return $this;
	}

}