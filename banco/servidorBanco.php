<?php

header('Content-type: text/html; charset=utf-8');

/* Parámetros de conexión con la base de datos. */
define('HOST', 'localhost');
define('USER', 'admin');
define('PASS', 'Banco33Admin');
define('DBASE', 'bancoalcala');

/*00webhost
DB NAME bancoalcala
DB USERNAME admin
DB PASSWORD Administrator33@

*/

class BaseDatos {
    
    private $conn;

    /**
     * Nuevo intento de conexión con el sistema.
     */
    public function __construct() {
        $this->conn = new mysqli(HOST, USER, PASS, DBASE);
        if ($this->conn->connect_errno) {
            throw new Exception("Error de conexión con la base de datos");
        }
		$this->conn->set_charset('utf8');
    }

	// Función que autoriza el acceso a la cuenta con el pin
	public function autenticar(string $cuenta, string $pin) {
		$correcto=["respuesta"=>"correcto"];
		$incorrecto=["respuesta"=>"incorrecto"];
	    $sql = "select * from cuenta where Cuenta = '$cuenta' && Pin = '$pin';";
        return $query = $this->conn->query($sql)->num_rows > 0 ? $correcto : $incorrecto;
    }
	
	// Ingreso de dinero
	public function ingreso(string $cantidad, string $cuenta){
		$cantidad=floatval($cantidad);
		$query="UPDATE cuenta SET Saldo = Saldo + $cantidad WHERE Cuenta=$cuenta";
		$stmt = $this->conn->prepare($query);
        return $stmt->execute();
	}
	
	//Retirada de efectivo
	public function retirada(string $cantidad, string $cuenta){
		$cantidad=floatval($cantidad);
		$query="UPDATE cuenta SET Saldo = Saldo - $cantidad WHERE Cuenta=$cuenta";
		$stmt = $this->conn->prepare($query);
        return $stmt->execute();
	}
	
	// Función que determina si una operación es posible, es decir, si la cantidad a retirar no supera el saldo de la cuenta
	public function operacionPosible(string $cantidad, string $cuenta){
		$sql = "SELECT Saldo FROM cuenta where Cuenta = $cuenta";
        $query = $this->conn->query($sql);
		$row = $query->fetch_assoc();
		$saldo = $row["Saldo"];
		if(floatval($saldo)>floatval($cantidad)){
			return true;
		}
		else{
			return false;
		}
	}
	
	// Efectúa una transferencia entre cuentas
	public function transferencia($cantidad, $origen, $destino){
		$query="UPDATE cuenta SET Saldo = Saldo - $cantidad WHERE Cuenta = $origen";
		$stmt = $this->conn->prepare($query);
        if($stmt->execute()){
			$query2="UPDATE cuenta SET Saldo = Saldo + $cantidad WHERE Cuenta = $destino";
			$stmt2 = $this->conn->prepare($query2);
			return $stmt2->execute();
		}
		else{
			return false;
		}
	}
	
	// Efectúa una recarga telefónica
	public function recarga($cantidad, $cuenta){
		$query="UPDATE cuenta SET Saldo = Saldo - $cantidad WHERE Cuenta = $cuenta";
		$stmt = $this->conn->prepare($query);
        return $stmt->execute();
	}
	
	// Registra un movimiento en el sistema
	public function addMovimiento($tipo, $cantidad, $cuenta, $localidad, $pais){
		$descripcion=$tipo." de ".$cantidad." en la cuenta ".$cuenta;
		$query=("INSERT INTO movimientos (Cuenta,Descripcion,Localidad,Pais) VALUES (?,?,?,?)");
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param('ssss', $cuenta,$descripcion,$localidad,$pais);
        return $stmt->execute();
	}
	// Registra un movimiento en el sistema
	public function addMovimiento2($tipo, $cantidad, $origen, $destino, $localidad, $pais){
		$descripcion=$tipo." de ".$cantidad." de la cuenta ".$origen." a la cuenta ".$destino;
		$query="INSERT INTO movimientos (Cuenta,Descripcion,Localidad,Pais) VALUES (?,?,?,?)";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param('ssss', $origen,$descripcion, $localidad, $pais);
        return $stmt->execute();
	}
	// Registra un movimiento en el sistema
	public function addMovimiento3($tipo, $cantidad, $origen, $destino, $localidad, $pais){
		$descripcion=$tipo." de ".$cantidad." de la cuenta ".$origen." al telefono ".$destino;
		$query="INSERT INTO movimientos (Cuenta,Descripcion,Localidad,Pais) VALUES (?,?,?,?)";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param('ssss', $origen,$descripcion, $localidad, $pais);
        return $stmt->execute();
	}
	
	//Devuelve 10 ultimos movimientos
	public function dameMovimientos(string $cuenta){
		$movimientos = [];
		$sql="SELECT Descripcion, Fecha, Localidad, Pais FROM movimientos WHERE Cuenta=$cuenta ORDER BY Fecha DESC LIMIT 10";
        $query = $this->conn->query($sql);
        while ($row = $query->fetch_assoc()) {
            array_push($movimientos, $row);
        }
        return $movimientos;
	}
	
	//Crea una nueva cuenta generando un pin aleatorio de 4 digitos
	public function nuevaCuenta(){
		$pin=rand(0,9).rand(0,9).rand(0,9).rand(0,9);
		$saldo=1250;
		$query="INSERT INTO cuenta (Pin,Saldo) VALUES (?,?)";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param('ii', $pin,$saldo);
        if($stmt->execute()){
			return $pin;
		}
		else{
			return "Error";
		}
	}
	
	// Devuelve la última cuenta creada con un pin determinado
	public function dameCuenta($pin){
		$sql="SELECT * FROM cuenta WHERE Pin=$pin ORDER BY Cuenta DESC LIMIT 1";
		$query = $this->conn->query($sql);
		$row = $query->fetch_assoc();
        return $row;
	}
	
	// Determina si una cuenta existe
	public function existeCuenta($cuenta){
		$sql = "select * from cuenta where Cuenta = $cuenta";
        return $query = $this->conn->query($sql)->num_rows > 0 ? true : false;
	}
	
	// Devuelve el saldo de una cuenta
	public function dameSaldo($cuenta){
		$sql="SELECT Saldo FROM cuenta WHERE Cuenta=$cuenta";
		$query = $this->conn->query($sql);
		$row = $query->fetch_assoc();
        return $row;
	}
	
	public function error($texto){
		$error=["Error"=>$texto];
		return $error;
	}
	
}

$db = new BaseDatos();

/* Nueva Cuenta */
if (isset($_GET['accion']) && $_GET['accion'] === "nuevaCuenta") {
	$pin=$db->nuevaCuenta();
	if($pin!="Error"){
		print json_encode($db->dameCuenta($pin));
	}
	else{
		print json_encode($db->error("No se ha podido crear la cuenta"));
	}
}

/* Autenticación. */
if (isset($_POST['cuenta']) && isset($_POST['pin'])) {
	print json_encode($db->autenticar($_POST['cuenta'],$_POST['pin']));
}

/* Ingreso de Efectivo. */
if (isset($_GET['accion']) && $_GET['accion'] === "ingreso") {
	if($db->ingreso($_GET['cantidad'],$_GET['cuenta'])){
		if($db->addMovimiento("Ingreso",$_GET['cantidad'],$_GET['cuenta'],$_GET['localidad'],$_GET['pais'])){
			print json_encode(["respuesta"=>"correcta"]);
		}
		else{
			print json_encode(["respuesta"=>"Fallo al añadir movimiento al histórico"]);
		}
	}
	else{
		print json_encode(["respuesta"=>"Fallo al actualizar el ingreso"]);
	}
}

/* Retirada de Efectivo. */
if (isset($_GET['accion']) && $_GET['accion'] === "retirada") {
	if($db->operacionPosible($_GET['cantidad'],$_GET['cuenta'])){
		if($db->retirada($_GET['cantidad'],$_GET['cuenta'])){
			if($db->addMovimiento("Retirada",$_GET['cantidad'],$_GET['cuenta'],$_GET['localidad'],$_GET['pais'])){
				print json_encode(["respuesta"=>"correcta"]);
			}
			else{
				print json_encode(["respuesta"=>"Fallo al añadir movimiento al histórico"]);
			}
		}
		else{
			print json_encode(["respuesta"=>"Fallo al actualizar la retirada"]);
		}
	}
	else{
		print json_encode(["respuesta"=>"La cantidad a retirar supera el saldo de la cuenta"]);
	}
}

/* Consulta de Saldo */
if (isset($_GET['accion']) && $_GET['accion'] === "saldo") {
	print json_encode($db->dameSaldo($_GET['cuenta']));
}

/* Devuelve 10 últimos movimientos de la Cuenta dada */
if (isset($_GET['accion']) && $_GET['accion'] === "movimientos") {
	print json_encode($db->dameMovimientos($_GET['cuenta']));
}

/* Efectúa una transferencia entre cuentas existentes */
if (isset($_GET['accion']) && $_GET['accion'] === "transferencia") {
	if($db->operacionPosible($_GET['cantidad'],$_GET['origen']) and $db->existeCuenta($_GET['destino'])){
		if($db->transferencia($_GET['cantidad'],$_GET['origen'],$_GET['destino'])){
			$db->addMovimiento2("Transferencia",$_GET['cantidad'],$_GET['origen'],$_GET['destino'],$_GET['localidad'],$_GET['pais']);
			print json_encode(["respuesta"=>"correcta"]);
		}
		else{
			print json_encode($db->error("Error al realizar la transferencia"));
		}
	}
	else{
		print json_encode($db->error("Error al realizar la transferencia. No hay saldo suficiente o la cuenta de destino no existe."));
	}
}

/* Efectúa una recarga telefónica */
if (isset($_GET['accion']) && $_GET['accion'] === "recarga") {
	if($db->operacionPosible($_GET['cantidad'],$_GET['cuenta'])){
		if($db->recarga($_GET['cantidad'],$_GET['cuenta'])){
			$db->addMovimiento3("Recarga",$_GET['cantidad'],$_GET['cuenta'],$_GET['telefono'],$_GET['localidad'],$_GET['pais']);
			print json_encode(["respuesta"=>"correcta"]);
		}
		else{
			print json_encode($db->error("Error al realizar la recarga"));
		}
	}
	else{
		print json_encode($db->error("Error al realizar la recarga. No hay saldo suficiente."));
	}
}

?>