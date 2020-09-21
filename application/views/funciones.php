<?php

class funciones {

    public function RegistrarTerceroDMS($id_conductor, $id_propietario, $id_tenedor) {
        //var_dump($id_conductor);exit;
        if($id_conductor){

            $conductor = Terceros::model()->findByAttributes(array("nit"=>$id_conductor), array('select' => 'nit,digito,nombres,nit_real,direccion,ciudad,telefono_1,tipo_identificacion,pais, regimen,fecha_creacion, y_dpto,y_ciudad,y_pais,Usuario'));
           
            if (!$conductor){
                 $conductor = new Terceros();
            
                $sidconductor = Conductores::model()->findByAttributes(array("cedula" => $id_conductor));
                $ciudad = Ciudades::model()->findByPk($sidconductor->ciudad);

                $conductor->nit = intval($sidconductor->cedula);
                $conductor->digito = 0;//funciones::calcularDV($sidconductor->cedula);
                $conductor->nombres = $sidconductor->apellidos . " " . $sidconductor->nombres;
                $conductor->pos_num = strlen($sidconductor->apellidos);
                $conductor->nit_real = $sidconductor->cedula;
                $conductor->direccion = substr($sidconductor->direccion, 0, 59);
                $conductor->ciudad = substr($ciudad->ciudad, 0, 19);
                $conductor->telefono_1 = substr($sidconductor->telefono, 0, 14);
                $conductor->tipo_identificacion = "C";
                $conductor->pais = "COLOMBIA";
                $conductor->regimen = "S";
                $conductor->fecha_creacion = date("Ymd H:i:s");

                $conductor->y_dpto = str_pad($ciudad->codigo_departamento, 2, "0", STR_PAD_LEFT);
                $conductor->y_ciudad = str_pad($ciudad->codigo, 3, "0", STR_PAD_LEFT);
                $conductor->y_pais = 169;

                $conductor->Usuario = "SISTEMAS";
                if ($conductor->save()) {

                    $nombres = explode(" ", trim($sidconductor->nombres));
                    $apellidos = explode(" ", trim($sidconductor->apellidos));

                    $conductornombre = TercerosNombres::model()->findByAttributes(array('nit' => $sidconductor->cedula), array('select' => 'nit,primer_apellido,segundo_apellido,primer_nombre,segundo_nombre'));

                    if (!$conductornombre) {
                        $conductornombre = new TercerosNombres();
                        $conductornombre->nit = intval($sidconductor->cedula);
                        $conductornombre->primer_apellido = $apellidos[0] ? $apellidos[0] : "";
                        $conductornombre->segundo_apellido = $apellidos[1] ? $apellidos[1] : "";
                        $conductornombre->primer_nombre = $nombres[0] ? $nombres[0] : "";
                        $conductornombre->segundo_nombre = $nombres[1] ? $nombres[1] : "";
                        $conductornombre->save();
                    }
                } else {
                    var_dump($conductor->errors);
                }
            }
        }

/*----------------------------------------------------------Propietarios------------------------------------------------------------------------*/
        if($id_propietario){

            $propietario = Terceros::model()->findByAttributes(array('nit'=>$id_propietario), array('select' => 'nit,digito,nombres,nit_real,direccion,ciudad,telefono_1,tipo_identificacion,pais, regimen,fecha_creacion, y_dpto,y_ciudad,y_pais,Usuario'));

            if (!$propietario) {
                $propietario = new Terceros();

                $sidPropietarios = Propietarios::model()->findByAttributes(array("identificacion" => $id_propietario));
                $ciudadP = Ciudades::model()->findByPk($sidPropietarios->ciudad);
                
                $propietario->nit = $sidPropietarios->identificacion;
                $propietario->digito = $sidPropietarios->tipo_identificacion == 1 ? funciones::calcularDV($sidPropietarios->identificacion) : 0;

                if($sidPropietarios->tipo_identificacion==1){
                    $propietario->nombres = trim($sidPropietarios->nombres." ".$sidPropietarios->apellidos);
                }
                else{
                    $propietario->nombres = trim($sidPropietarios->apellidos . " " . $sidPropietarios->nombres);
                     $propietario->pos_num = strlen($sidPropietarios->apellidos);
                }

                $propietario->nit_real = $sidPropietarios->identificacion;
                $propietario->direccion = $sidPropietarios->direccion;
                $propietario->ciudad = $ciudadP->ciudad;
                $propietario->telefono_1 = $sidPropietarios->telefono;

                $propietario->tipo_identificacion = $sidPropietarios->tipo_identificacion == 1 ? "N" : "C";
                $propietario->pais = "COLOMBIA";
                $propietario->regimen = $sidPropietarios->tipo_identificacion == 1 ? "C" : "S";
                $propietario->fecha_creacion = date("Ymd H:i:s");

                $propietario->y_dpto = str_pad($ciudadP->codigo_departamento, 2, "0", STR_PAD_LEFT);
                $propietario->y_ciudad = str_pad($ciudadP->codigo, 3, "0", STR_PAD_LEFT);
                $propietario->y_pais = 169;

                $propietario->Usuario = "SISTEMAS";
                //var_dump($propietario);exit;
                if ($propietario->save()) {

                    $nombres = explode(" ", trim($sidPropietarios->nombres));
                    $apellidos = explode(" ", trim($sidPropietarios->apellidos));


                    $propietanombre = TercerosNombres::model()->findByAttributes(array('nit' => $sidPropietarios->identificacion), array('select' => 'nit,primer_apellido,segundo_apellido,primer_nombre,segundo_nombre'));

                    if (!$propietanombre) {
                        $propietanombre = new TercerosNombres();
                        $propietanombre->nit = intval($sidPropietarios->identificacion);
                        if($sidPropietarios->tipo_identificacion==1){
                            $propietanombre->primer_apellido = $sidPropietarios->apellidos;
                            $propietanombre->segundo_apellido = "";
                            $propietanombre->primer_nombre = $sidPropietarios->nombres;
                            $propietanombre->segundo_nombre = "";
                        } else{
                            $propietanombre->primer_apellido = $apellidos[0] ? $apellidos[0] : "";
                            $propietanombre->segundo_apellido = $apellidos[1] ? $apellidos[1] : "";
                            $propietanombre->primer_nombre = $nombres[0] ? $nombres[0] : "";
                            $propietanombre->segundo_nombre = $nombres[1] ? $nombres[1] : "";
                        }
                        $propietanombre->save();
                    }
                } else {
                    var_dump($propietario->errors);
                }
            }
        }

        if($id_tenedor){

            $tenedor = Terceros::model()->findByAttributes(array('nit'=>$id_tenedor), array('select' => 'nit,digito,nombres,nit_real,direccion,ciudad,telefono_1,tipo_identificacion,pais, regimen,fecha_creacion, y_dpto,y_ciudad,y_pais,Usuario'));

            if (!$tenedor) {
                $tenedor = new Terceros();

                $sidtenedor = Tenedores::model()->findByAttributes(array("identificacion" => $id_tenedor));
                $ciudadT = Ciudades::model()->findByPk($sidtenedor->ciudad);
                $tenedor->nit = intval($sidtenedor->identificacion);
                $tenedor->digito = $sidtenedor->tipo_identificacion == 1 ? funciones::calcularDV($sidtenedor->identificacion) : 0;;

                if($sidtenedor->tipo_identificacion==1){
                    $tenedor->nombres = trim($sidtenedor->nombres." ".$sidtenedor->apellidos);    
                }
                else{
                    $tenedor->nombres = trim($sidtenedor->apellidos . " " . $sidtenedor->nombres);
                    $tenedor->pos_num = strlen($sidtenedor->apellidos);
                }
                
                $tenedor->pos_num = strlen($sidtenedor->apellidos);
                $tenedor->nit_real = $sidtenedor->identificacion;
                $tenedor->direccion = substr($sidtenedor->direccion, 0, 59);
                $tenedor->ciudad = $ciudadT ? substr($ciudadT->ciudad, 0, 19) : NULL;
                $tenedor->telefono_1 = substr($sidtenedor->telefono, 0, 14);

                $tenedor->tipo_identificacion = $sidtenedor->tipo_identificacion == 1 ? "N" : "C";
                $tenedor->pais = "COLOMBIA";
                $tenedor->regimen = $sidtenedor->tipo_identificacion == 1 ? "C" : "S";
                $tenedor->fecha_creacion = date("Ymd H:i:s");

                $tenedor->y_dpto = str_pad($ciudadT->codigo_departamento, 2, "0", STR_PAD_LEFT);
                $tenedor->y_ciudad = str_pad($ciudadT->codigo, 3, "0", STR_PAD_LEFT);
                $tenedor->y_pais = 169;

                $tenedor->Usuario = "SISTEMAS";

                if ($tenedor->save()) {

                    $nombres = explode(" ", trim($sidtenedor->nombres));
                    $apellidos = explode(" ", trim($sidtenedor->apellidos));

                    $tenedornombre = TercerosNombres::model()->findByAttributes(array('nit' => $sidtenedor->identificacion), array('select' => 'nit,primer_apellido,segundo_apellido,primer_nombre,segundo_nombre'));

                    if (!$tenedornombre) {
                        $tenedornombre = new TercerosNombres();
                        $tenedornombre->nit = intval($sidtenedor->identificacion);
                        if($sidtenedor->tipo_identificacion==1){
                            $tenedornombre->primer_apellido = $sidtenedor->apellidos;
                            $tenedornombre->segundo_apellido = "";
                            $tenedornombre->primer_nombre = $sidtenedor->nombres;
                            $tenedornombre->segundo_nombre = "";
                        } else{
                            $tenedornombre->primer_apellido = $apellidos[0] ? $apellidos[0] : "";
                            $tenedornombre->segundo_apellido = $apellidos[1] ? $apellidos[1] : "";
                            $tenedornombre->primer_nombre = $nombres[0] ? $nombres[0] : "";
                            $tenedornombre->segundo_nombre = $nombres[1] ? $nombres[1] : "";
                        }
                        $tenedornombre->save();
                    }
                } else {
                    var_dump($tenedor->errors);
                }
            
            }
        }


        if (($propietario) || ($tenedor) || ($conductor)) {
            return true;
        } else {
            return false;
        }
    }

    public function RegistrarTerceroDMS2($id_conductor, $id_propietario, $id_tenedor) {
        //var_dump($id_conductor);exit;
        if($id_conductor){

            $conductor = Terceros::model()->findByAttributes(array("nit"=>$id_conductor), array('select' => 'nit,digito,nombres,nit_real,direccion,ciudad,telefono_1,tipo_identificacion,pais, regimen,fecha_creacion, y_dpto,y_ciudad,y_pais,Usuario'));
           
            if (!$conductor)
                 $conductor = new Terceros();
            
            $sidconductor = Conductores::model()->findByAttributes(array("cedula" => $id_conductor));
            $ciudad = Ciudades::model()->findByPk($sidconductor->ciudad);

            $conductor->nit = intval($sidconductor->cedula);
            $conductor->digito = 0;//funciones::calcularDV($sidconductor->cedula);
            $conductor->nombres = $sidconductor->apellidos . " " . $sidconductor->nombres;
            $conductor->pos_num = strlen($sidconductor->apellidos);
            $conductor->nit_real = $sidconductor->cedula;
            $conductor->direccion = substr($sidconductor->direccion, 0, 59);
            $conductor->ciudad = substr($ciudad->ciudad, 0, 19);
            $conductor->telefono_1 = substr($sidconductor->telefono, 0, 14);
            $conductor->tipo_identificacion = "C";
            $conductor->pais = "COLOMBIA";
            $conductor->regimen = "S";
            $conductor->fecha_creacion = date("Ymd H:i:s");

            $conductor->y_dpto = str_pad($ciudad->codigo_departamento, 2, "0", STR_PAD_LEFT);
            $conductor->y_ciudad = str_pad($ciudad->codigo, 3, "0", STR_PAD_LEFT);
            $conductor->y_pais = 169;

            $conductor->Usuario = "SISTEMAS";
            echo $sidconductor->cedula."<br>";
            if ($conductor->save()) {

                $nombres = explode(" ", trim($sidconductor->nombres));
                $apellidos = explode(" ", trim($sidconductor->apellidos));

                $conductornombre = TercerosNombres::model()->findByAttributes(array('nit' => $sidconductor->cedula), array('select' => 'nit,primer_apellido,segundo_apellido,primer_nombre,segundo_nombre'));

                if ($conductornombre)
                    $conductornombre->delete(); 

                $conductornombre = new TercerosNombres();
                $conductornombre->nit = intval($sidconductor->cedula);
                $conductornombre->primer_apellido = $apellidos[0] ? $apellidos[0] : "";
                $conductornombre->segundo_apellido = $apellidos[1] ? $apellidos[1] : "";
                $conductornombre->primer_nombre = $nombres[0] ? $nombres[0] : "";
                $conductornombre->segundo_nombre = $nombres[1] ? $nombres[1] : "";
                $conductornombre->save();
                
            } else {
                echo "cedula: $sidConductor->cedula<br>";
                var_dump($conductor->errors);
                echo "<br>";
                //var_dump($conductor->errors);
            }
        }

/*----------------------------------------------------------Propietarios------------------------------------------------------------------------*/
        if($id_propietario){

            $propietario = Terceros::model()->findByAttributes(array('nit'=>$id_propietario), array('select' => 'nit,digito,nombres,nit_real,direccion,ciudad,telefono_1,tipo_identificacion,pais, regimen,fecha_creacion, y_dpto,y_ciudad,y_pais,Usuario'));
            if (!$propietario)
                $propietario = new Terceros();

            $sidPropietarios = Propietarios::model()->findByAttributes(array("identificacion" => $id_propietario));
            $ciudadP = Ciudades::model()->findByPk($sidPropietarios->ciudad);
            
            $propietario->nit = $sidPropietarios->identificacion;
            $propietario->digito = $sidPropietarios->tipo_identificacion == 1 ? funciones::calcularDV($sidPropietarios->identificacion) : 0;
            if($sidPropietarios->tipo_identificacion==1){
                $propietario->nombres = trim($sidPropietarios->nombres." ".$sidPropietarios->apellidos);
            }
            else{
                $propietario->nombres = trim($sidPropietarios->apellidos . " " . $sidPropietarios->nombres);
                $propietario->pos_num = strlen($sidPropietarios->apellidos);
            }

            $propietario->nit_real = $sidPropietarios->identificacion;
            $propietario->direccion = substr($sidPropietarios->direccion, 0, 59);
            $propietario->ciudad = $ciudadP->ciudad;
            $propietario->telefono_1 = $sidPropietarios->telefono;

            $propietario->tipo_identificacion = $sidPropietarios->tipo_identificacion == 1 ? "N" : "C";
            $propietario->pais = "COLOMBIA";
            $propietario->regimen = $sidPropietarios->tipo_identificacion == 1 ? "C" : "S";
            $propietario->fecha_creacion = date("Ymd H:i:s");

            $propietario->y_dpto = str_pad($ciudadP->codigo_departamento, 2, "0", STR_PAD_LEFT);
            $propietario->y_ciudad = str_pad($ciudadP->codigo, 3, "0", STR_PAD_LEFT);
            $propietario->y_pais = 169;

            $propietario->Usuario = "SISTEMAS";
            //var_dump($propietario);exit;
            echo $sidPropietarios->identificacion."<br>";
            if ($propietario->save()) {

                $nombres = explode(" ", trim($sidPropietarios->nombres));
                $apellidos = explode(" ", trim($sidPropietarios->apellidos));

                $propietanombre = TercerosNombres::model()->findByAttributes(array('nit' => $sidPropietarios->identificacion), array('select' => 'nit,primer_apellido,segundo_apellido,primer_nombre,segundo_nombre'));

                if ($propietanombre)
                    $propietanombre->delete();

                $propietanombre = new TercerosNombres();
                $propietanombre->nit = intval($sidPropietarios->identificacion);
                if($sidPropietarios->tipo_identificacion==1){
                    $propietanombre->primer_apellido = $sidPropietarios->apellidos;
                    $propietanombre->segundo_apellido = "";
                    $propietanombre->primer_nombre = $sidPropietarios->nombres;
                    $propietanombre->segundo_nombre = "";
                } else{
                    $propietanombre->primer_apellido = $apellidos[0] ? $apellidos[0] : "";
                    $propietanombre->segundo_apellido = $apellidos[1] ? $apellidos[1] : "";
                    $propietanombre->primer_nombre = $nombres[0] ? $nombres[0] : "";
                    $propietanombre->segundo_nombre = $nombres[1] ? $nombres[1] : "";
                }
                $propietanombre->save();
        
            } else {
                echo "cedula: $sidPropietarios->identificacion<br>";
                var_dump($propietario->errors);
                echo "<br>";
            }
        }

        if($id_tenedor){

            $tenedor = Terceros::model()->findByAttributes(array('nit'=>$id_tenedor), array('select' => 'nit,digito,nombres,nit_real,direccion,ciudad,telefono_1,tipo_identificacion,pais, regimen,fecha_creacion, y_dpto,y_ciudad,y_pais,Usuario'));

            if (!$tenedor)
                $tenedor = new Terceros();

            $sidtenedor = Tenedores::model()->findByAttributes(array("identificacion" => $id_tenedor));
            $ciudadT = Ciudades::model()->findByPk($sidtenedor->ciudad);
            $tenedor->nit = intval($sidtenedor->identificacion);
            $tenedor->digito = $sidtenedor->tipo_identificacion == 1 ? funciones::calcularDV($sidtenedor->identificacion) : 0;;

            if($sidtenedor->tipo_identificacion==1){
                $tenedor->nombres = trim($sidtenedor->nombres." ".$sidtenedor->apellidos);    
            }
            else{
                $tenedor->nombres = trim($sidtenedor->apellidos . " " . $sidtenedor->nombres);
                $tenedor->pos_num = strlen($sidtenedor->apellidos);
            }
            
            $tenedor->nit_real = $sidtenedor->identificacion;
            $tenedor->direccion = substr($sidtenedor->direccion, 0, 59);
            $tenedor->ciudad = $ciudadT ? substr($ciudadT->ciudad, 0, 19) : NULL;
            $tenedor->telefono_1 = substr($sidtenedor->telefono, 0, 14);

            $tenedor->tipo_identificacion = $sidtenedor->tipo_identificacion == 1 ? "N" : "C";
            $tenedor->pais = "COLOMBIA";
            $tenedor->regimen = $sidtenedor->tipo_identificacion == 1 ? "C" : "S";
            $tenedor->fecha_creacion = date("Ymd H:i:s");

            $tenedor->y_dpto = str_pad($ciudadT->codigo_departamento, 2, "0", STR_PAD_LEFT);
            $tenedor->y_ciudad = str_pad($ciudadT->codigo, 3, "0", STR_PAD_LEFT);
            $tenedor->y_pais = 169;

            $tenedor->Usuario = "SISTEMAS";
            echo $sidTenedor->identificacion."<br>";
            if ($tenedor->save()) {

                $nombres = explode(" ", trim($sidtenedor->nombres));
                $apellidos = explode(" ", trim($sidtenedor->apellidos));

                $tenedornombre = TercerosNombres::model()->findByAttributes(array('nit' => $sidtenedor->identificacion), array('select' => 'nit,primer_apellido,segundo_apellido,primer_nombre,segundo_nombre'));

                if ($tenedornombre)
                    $tenedornombre->delete();

                $tenedornombre = new TercerosNombres();
                $tenedornombre->nit = intval($sidtenedor->identificacion);
                if($sidtenedor->tipo_identificacion==1){
                    $tenedornombre->primer_apellido = $sidtenedor->apellidos;
                    $tenedornombre->segundo_apellido = "";
                    $tenedornombre->primer_nombre = $sidtenedor->nombres;
                    $tenedornombre->segundo_nombre = "";
                } else{
                    $tenedornombre->primer_apellido = $apellidos[0] ? $apellidos[0] : "";
                    $tenedornombre->segundo_apellido = $apellidos[1] ? $apellidos[1] : "";
                    $tenedornombre->primer_nombre = $nombres[0] ? $nombres[0] : "";
                    $tenedornombre->segundo_nombre = $nombres[1] ? $nombres[1] : "";
                }
                
                $tenedornombre->save();
    
            } else {
                echo "cedula: $sidTenedor->identificacion<br>";
                var_dump($tenedor->errors);
                echo "<br>";
            }
        }


        if (($propietario) || ($tenedor) || ($conductor)) {
            return true;
        } else {
            return false;
        }
    }

    public function num2letras($num, $fem = false, $dec = true) {
        $matuni[2] = "dos";
        $matuni[3] = "tres";
        $matuni[4] = "cuatro";
        $matuni[5] = "cinco";
        $matuni[6] = "seis";
        $matuni[7] = "siete";
        $matuni[8] = "ocho";
        $matuni[9] = "nueve";
        $matuni[10] = "diez";
        $matuni[11] = "once";
        $matuni[12] = "doce";
        $matuni[13] = "trece";
        $matuni[14] = "catorce";
        $matuni[15] = "quince";
        $matuni[16] = "dieciseis";
        $matuni[17] = "diecisiete";
        $matuni[18] = "dieciocho";
        $matuni[19] = "diecinueve";
        $matuni[20] = "veinte";
        $matunisub[2] = "dos";
        $matunisub[3] = "tres";
        $matunisub[4] = "cuatro";
        $matunisub[5] = "quin";
        $matunisub[6] = "seis";
        $matunisub[7] = "sete";
        $matunisub[8] = "ocho";
        $matunisub[9] = "nove";

        $matdec[2] = "veint";
        $matdec[3] = "treinta";
        $matdec[4] = "cuarenta";
        $matdec[5] = "cincuenta";
        $matdec[6] = "sesenta";
        $matdec[7] = "setenta";
        $matdec[8] = "ochenta";
        $matdec[9] = "noventa";
        $matsub[3] = 'mill';
        $matsub[5] = 'bill';
        $matsub[7] = 'mill';
        $matsub[9] = 'trill';
        $matsub[11] = 'mill';
        $matsub[13] = 'bill';
        $matsub[15] = 'mill';
        $matmil[4] = 'millones';
        $matmil[6] = 'billones';
        $matmil[7] = 'de billones';
        $matmil[8] = 'millones de billones';
        $matmil[10] = 'trillones';
        $matmil[11] = 'de trillones';
        $matmil[12] = 'millones de trillones';
        $matmil[13] = 'de trillones';
        $matmil[14] = 'billones de trillones';
        $matmil[15] = 'de billones de trillones';
        $matmil[16] = 'millones de billones de trillones';

        //Zi hack
        $float = explode('.', $num);
        $num = $float[0];

        $num = trim((string) @$num);
        if ($num[0] == '-') {
            $neg = 'menos ';
            $num = substr($num, 1);
        } else
            $neg = '';
        while ($num[0] == '0')
            $num = substr($num, 1);
        if ($num[0] < '1' or $num[0] > 9)
            $num = '0' . $num;
        $zeros = true;
        $punt = false;
        $ent = '';
        $fra = '';
        for ($c = 0; $c < strlen($num); $c++) {
            $n = $num[$c];
            if (!(strpos(".,'''", $n) === false)) {
                if ($punt)
                    break;
                else {
                    $punt = true;
                    continue;
                }
            } elseif (!(strpos('0123456789', $n) === false)) {
                if ($punt) {
                    if ($n != '0')
                        $zeros = false;
                    $fra .= $n;
                } else
                    $ent .= $n;
            } else
                break;
        }
        $ent = '     ' . $ent;
        if ($dec and $fra and ! $zeros) {
            $fin = ' coma';
            for ($n = 0; $n < strlen($fra); $n++) {
                if (($s = $fra[$n]) == '0')
                    $fin .= ' cero';
                elseif ($s == '1')
                    $fin .= $fem ? ' una' : ' un';
                else
                    $fin .= ' ' . $matuni[$s];
            }
        } else
            $fin = '';
        if ((int) $ent === 0)
            return 'Cero ' . $fin;
        $tex = '';
        $sub = 0;
        $mils = 0;
        $neutro = false;
        while (($num = substr($ent, -3)) != '   ') {
            $ent = substr($ent, 0, -3);
            if (++$sub < 3 and $fem) {
                $matuni[1] = 'una';
                $subcent = 'as';
            } else {
                $matuni[1] = $neutro ? 'un' : 'uno';
                $subcent = 'os';
            }
            $t = '';
            $n2 = substr($num, 1);
            if ($n2 == '00') {
                
            } elseif ($n2 < 21)
                $t = ' ' . $matuni[(int) $n2];
            elseif ($n2 < 30) {
                $n3 = $num[2];
                if ($n3 != 0)
                    $t = 'i' . $matuni[$n3];
                $n2 = $num[1];
                $t = ' ' . $matdec[$n2] . $t;
            }else {
                $n3 = $num[2];
                if ($n3 != 0)
                    $t = ' y ' . $matuni[$n3];
                $n2 = $num[1];
                $t = ' ' . $matdec[$n2] . $t;
            }
            $n = $num[0];
            if ($n == 1) {
                $t = ' ciento' . $t;
            } elseif ($n == 5) {
                $t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t;
            } elseif ($n != 0) {
                $t = ' ' . $matunisub[$n] . 'cient' . $subcent . $t;
            }
            if ($sub == 1) {
                
            } elseif (!isset($matsub[$sub])) {
                if ($num == 1) {
                    $t = ' mil';
                } elseif ($num > 1) {
                    $t .= ' mil';
                }
            } elseif ($num == 1) {
                $t .= ' ' . $matsub[$sub] . '?n';
            } elseif ($num > 1) {
                $t .= ' ' . $matsub[$sub] . 'ones';
            }
            if ($num == '000')
                $mils ++;
            elseif ($mils != 0) {
                if (isset($matmil[$sub]))
                    $t .= ' ' . $matmil[$sub];
                $mils = 0;
            }
            $neutro = true;
            $tex = $t . $tex;
        }
        $tex = $neg . substr($tex, 1) . $fin;
        //Zi hack --> return ucfirst($tex);
        $end_num = ucfirst($tex) . ' pesos ' . $float[1] . '/100 M.N.';
        return $end_num;
    }

    function reactivar_session() {
        $usuario = Usuarios::model()->findByAttributes(array('usuario' => Yii::app()->user->name));
        $oficina = Oficinas::model()->findByPk($usuario->oficina);
        Yii::app()->session->add('usuarioCedula', $usuario->cedula);
        Yii::app()->session->add('oficina', $oficina->id);
    }

    public function SetClientesOperadores($clientes) {
        OperadoresClientes::model()->deleteAllByAttributes(array("usuario" => Yii::app()->session->get('usuarioCedula')));
        foreach ($clientes as $cliente) {
            if ($cliente) {
                $operadorCliente = new OperadoresClientes();
                $operadorCliente->cliente = $cliente;
                $operadorCliente->usuario = Yii::app()->session->get('usuarioCedula');
                $operadorCliente->save();
            }
        }
    }

    public function setAttributes($model, $post, $uppercase = true) {
        $p = new CHtmlPurifier();
        $p->options = array('URI.AllowedSchemes' => array('http' => true, 'https' => true,));
        foreach ($post as $key => $campo)
            $model->$key = $p->purify(trim($uppercase ? strtoupper($campo) : $campo));
        return $model;
    }

    function guardarArchivos($parametro, $files, $post) {
        $ruta = Yii::app()->basePath . "/../HVS/" . $parametro . "/";
        if (!is_dir($ruta)) {
            mkdir($ruta, 0777, true);
            chmod($ruta, 0777);
        }
        for ($i = 0; $i < count($files["name"]); $i++) {
            list(, $ext) = explode('.', $files["name"][$i]);
            echo "<pre>";
            if (strlen($post["desc"][$i]) > 0)
                $nombre = funciones::sanear_string($post["desc"][$i]) . "." . $ext;
            else
                $nombre = $files["name"][$i];
            move_uploaded_file($files["tmp_name"][$i], $ruta . $nombre);
        }
    }

    function guardarArchivosManifiestos($parametro, $files, $post) {
        $ruta = Yii::app()->basePath . "/../MANIFIESTOS/" . $parametro . "/";
        if (!is_dir($ruta)) {
            mkdir($ruta, 0777, true);
            chmod($ruta, 0777);
        }
        for ($i = 0; $i < count($files["name"]); $i++) {
            list(, $ext) = explode('.', $files["name"][$i]);
            echo "<pre>";
            if (strlen($post["desc"][$i]) > 0)
                $nombre = funciones::sanear_string($post["desc"][$i]) . "." . $ext;
            else
                $nombre = $files["name"][$i];
            move_uploaded_file($files["tmp_name"][$i], $ruta . $nombre);
        }
    }

    function listarArchivos($path, $id, $eliminar = false) {
        $opath = $path;
        echo '<div id="images" class="view" style="display: flex;">';
        funciones::recorrerPathArchivos($path, $id, $eliminar);
        echo '</div>';
    }

    function recorrerPathArchivos($path, $id, $eliminar = false) {
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
            chmod($path, 0777);
        }
        $doc = array('doc', 'docx');
        $xls = array('xls', 'xlsx');
        $pdf = array('pdf');
        $foto = array('jpg', 'jpeg', 'png', 'gif');
        $dir = opendir($path);
        while ($elemento = readdir($dir)) {
            if ($elemento != "." && $elemento != "..") {
                if (!is_dir($path . $elemento)) {
                    list($nombre, $ext) = explode('.', $elemento);
                    if (in_array($ext, $doc))
                        $img = "img/word.png";
                    elseif (in_array($ext, $xls))
                        $img = "img/xls.png";
                    elseif (in_array($ext, $pdf))
                        $img = "img/pdf.png";
                    elseif (in_array($ext, $foto))
                        $img = $path . $elemento;
                    else
                        $img = 'img/no_definido.png';
                    $ran = "div" . rand(1, 999999);
                    echo '<div align="center" style="margin-right: 10px;" class="box" id="' . $ran . '">';
                    echo CHtml::link(CHtml::image("$img", $elemento, array('style' => 'margin:10px;height:70px;width:70px;', 'rel' => "gallery")), $path . $elemento, array('target' => '_blank'));
                    echo "<br> <label style='margin: 0px 5px 8px 5px;'>" . $nombre . "</label>";
                    if ($eliminar) {
                        echo '<br>';
                        echo CHtml::ajaxLink(
                                $text = CHtml::image("img/cancel-on.png", 'Eliminar'), array('site/eliminarImg', 'id' => $id, 'iden' => $elemento, 'ruta' => '/' . $path), $ajaxOptions = array(
                            'type' => 'POST',
                            'success' => 'function(data){if(data==1){ $( "#' . $ran . '" ).remove();}}'
                                ), array("onclick" => "if (!confirm('Esta seguro?\\r\\nSi elimina este archivo NO podra recuperarlo.')){return}")
                        );
                    }
                    echo '</div>';
                }
            }
        }
    }

    public function Get_roles() {
        $arrayAuthRoleItems = Yii::app()->authManager->getAuthItems(2, Yii::app()->user->getId());
        return array_keys($arrayAuthRoleItems);
    }

    public function key() {
        return "*#4r4ng0b3*-";
    }

    public function encrypt($pure_string) {
        $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $encrypted_string = mcrypt_encrypt(MCRYPT_BLOWFISH, funciones::key(), utf8_encode($pure_string), MCRYPT_MODE_ECB, $iv);
        return base64_encode($encrypted_string);
    }

    public function decrypt($encrypted_string) {
        $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $decrypted_string = mcrypt_decrypt(MCRYPT_BLOWFISH, funciones::key(), base64_decode($encrypted_string), MCRYPT_MODE_ECB, $iv);
        return trim(stripslashes($decrypted_string));
    }

    function prueba() {
        $ar = fopen("datos" . date("Hi:s") . ".txt", "a");
        fputs($ar, "\n");
        fputs($ar, ':)');
        fclose($ar);
    }

    function cargarImagenes($registro) {
        ini_set('post_max_size', '200M');
        $imagenes = array();
        $path = 'images/fotos/';
        for ($ii = 0; $ii <= 5; $ii++) {
            if ($ii == 0)
                $nombres[$ii] = $registro;
            else
                $nombres[$ii] = $registro . "-" . $ii;
            $pesoMaximo = 3198576;
            if (is_file($path . $nombres[$ii] . ".jpg") && exif_imagetype($path . $nombres[$ii] . ".jpg")) {
                $pesoImagen = filesize($path . $nombres[$ii] . ".jpg");
                if ($pesoImagen <= $pesoMaximo) {
                    Yii::app()->thumbnailer->generate('images/fotos/' . $nombres[$ii] . ".jpg", $path . "miniaturas/thm" . $nombres[$ii] . ".jpg");
                    $imagenes[] = array('image' => $path . $nombres[$ii] . ".jpg", 'thumbnail' => $path . "miniaturas/thm" . $nombres[$ii] . ".jpg");
                }
            } elseif (is_file($path . $nombres[$ii] . ".JPG") && exif_imagetype($path . $nombres[$ii] . ".JPG")) {
                $pesoImagen = filesize($path . $nombres[$ii] . ".JPG");

                if ($pesoImagen <= $pesoMaximo) {
                    Yii::app()->thumbnailer->generate('images/fotos/' . $nombres[$ii] . ".JPG", $path . "miniaturas/thm" . $nombres[$ii] . ".jpg");
                    $imagenes[] = array('image' => $path . $nombres[$ii] . ".JPG", 'thumbnail' => $path . "miniaturas/thm" . $nombres[$ii] . ".jpg");
                }
            }
        }
        return $imagenes;
    }

    function guardarFotos($parametro, $files) {
        $parametro_ori = $parametro;
        $ruta = Yii::app()->basePath . "/../images/fotos/";
        for ($i = 0; $i < count($files["name"]); $i++) {
            for ($j = 1; $j <= 20; $j++) {
                if (file_exists($ruta . $parametro . ".jpg")) {
                    $parametro = $parametro_ori . "-" . $j;
                } else
                    break;
            }
            $pesoMaximo = 7340032;
            $pesoImagen = filesize($files["tmp_name"][$i]);
            if ($pesoImagen <= $pesoMaximo) {
                move_uploaded_file($files["tmp_name"][$i], $ruta . $parametro . ".jpg");
                if ($files["tmp_name"][$i])
                    Yii::app()->thumbnailer2->generate($ruta . $parametro . ".jpg", $ruta . $parametro . ".jpg");
            } else
                return false;
        }
    }

    function reducirImagen($filename) {
        $width = 500;
        $height = 500;

        header('Content-Type: image/jpeg');

        list($width_orig, $height_orig) = getimagesize($filename);

        $ratio_orig = $width_orig / $height_orig;

        if ($width / $height > $ratio_orig) {
            $width = $height * $ratio_orig;
        } else {
            $height = $width / $ratio_orig;
        }

// Resample
        $image_p = imagecreatetruecolor($width, $height);
        $image = imagecreatefromjpeg($filename);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);

// Output
        imagejpeg($image_p, null, 100);
    }

    function redimensionar_jpeg($img_original, $img_nueva, $img_nueva_anchura, $img_nueva_altura, $img_nueva_calidad) {
// crear una imagen desde el original
        $img = ImageCreateFromJPEG($img_original);
// crear una imagen nueva
        $thumb = imagecreatetruecolor($img_nueva_anchura, $img_nueva_altura);
// redimensiona la imagen original copiandola en la imagen
        ImageCopyResized($thumb, $img, 0, 0, 0, 0, $img_nueva_anchura, $img_nueva_altura, ImageSX($img), ImageSY($img));
// guardar la nueva imagen redimensionada donde indicia $img_nueva
        ImageJPEG($thumb, $img_nueva, $img_nueva_calidad);
        ImageDestroy($img);
    }
        function buscarDivipol($c){
        
        $codCiudad= Ciudades::model()->findByAttributes(array('id'=>$c));
        return $codCiudad->codigo_c;
    }

    function calcularDV($nit) {
        if (!is_numeric($nit))
            return false;

        $arr = array(1 => 3, 4 => 17, 7 => 29, 10 => 43, 13 => 59, 2 => 7, 5 => 19, 8 => 37, 11 => 47, 14 => 67, 3 => 13, 6 => 23, 9 => 41, 12 => 53, 15 => 71);
        $x = $y = 0;
        $z = strlen($nit);
        $dv = '';

        for ($i = 0; $i < $z; $i++) {
            $y = substr($nit, $i, 1);
            $x += ($y * $arr[$z - $i]);
        }
        $y = $x % 11;
        if ($y > 1) {
            $dv = 11 - $y;
            return $dv;
        } else {
            $dv = $y;
            return $dv;
        }
    }

    function get_client_ip() {
        $ipaddress = '';
        if ($_SERVER['HTTP_CLIENT_IP'])
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if ($_SERVER['HTTP_X_FORWARDED_FOR'])
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if ($_SERVER['HTTP_X_FORWARDED'])
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if ($_SERVER['HTTP_FORWARDED_FOR'])
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if ($_SERVER['HTTP_FORWARDED'])
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if ($_SERVER['REMOTE_ADDR'])
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';

        return $ipaddress;
    }

    function _Get_Tipos_documentos() {
        return array(
            1 => 'NIT',
            2 => 'CC',
            3 => 'DE',
            4 => 'TI',
            5 => 'CE',
        );
    }

    function _Get_Tipos_Vehiculos() {
        return array(
            1 => 'Automovil',
            2 => 'Motosicleta',
        );
    }

    function _Get_MarcasAutomoviles() {
        return array(
            "CHEVROLET" => "CHEVROLET",
            "DAEWOO" => "DAEWOO",
            "FIAT" => "FIAT",
            "FORD" => "FORD",
            "HYUNDAI" => "HYUNDAI",
            "KIA" => "KIA",
            "LADA" => "LADA",
            "MAZDA" => "MAZDA",
            "MITSUBISHI" => "MITSUBISHI",
            "PEUGEOT" => "PEUGEOT",
            "RENAULT" => "RENAULT",
            "SEAT" => "SEAT",
            "TOYOTA" => "TOYOTA",
            "YAMAHA" => "YAMAHA",
            "AUTECO-BAJAJ" => "AUTECO-BAJAJ",
            "KAWASAKI" => "KAWASAKI",
            "SUZUKI" => "SUZUKI",
            "HONDA" => "HONDA",
            "AKT" => "AKT",
            "LIFAN" => "LIFAN",
            "UM" => "UM",
        );
    }

    function _Get_Estados_Generales() {
        return array(
            '3' => 'Denegado',
            '2' => 'Verificar',
            '1' => 'Activo',
            '0' => 'Inactivo',
        );
    }

    function _Get_Playero() {
        return array(
            '1' => 'Si',
            '0' => 'No',
        );
    }


    function _Get_Estados_Manifiestos() {
        return array(
            '1' => 'Activo',
            '2' => 'Bloqueado',
            '3' => 'Reservado',
            '4' => 'Cumplido', //XXXXXXXXXXXXXXXXXXXXXXXXXXX
            '5' => 'Facturado', //XXXXXXXXXXXXXXXXXXXXXXXXXXX
            '-1' => 'Anulado',
            '0' => 'Liquidado',
        );
    }

    function _Get_tipos_adminbitacora2() { //XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
        return array(
            '1' => 'Interna',
            '2' => 'Externa',
        );
    }

    function _Get_tipos_adminbitacora($id) {
        $tipos = funciones::_Get_tipos_adminbitacora2();
        return $tipos[$id];
    }

//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

    function _Get_Estados_ManifiestosXID($id) {
        if ($id < 6) {   //Antes estaba con 4
            $estados = funciones::_Get_Estados_Manifiestos();
            return $estados[$id];
        }
        return "-";
    }

    function _Get_Estados_GeneralesXID($id) {
        $estados = funciones::_Get_Estados_Generales();
        return $estados[$id];
    }

    function _Get_UMedida() {
        return array(
            1 => 'Kilogramos',
            2 => 'Galones',
        );
    }

    function _Get_licencias() {
        return array(
            4 => 4,
            5 => 5,
            6 => 6,
            'C1' => 'C1',
            'C2' => 'C2',
            'C3' => 'C3'
        );
    }

    function _Get_CEmpaque() {
        return array(
            "PQT" => "PAQUETES",
            "CJS" => "CAJAS",
            "sacos" => "SCS",
            "BID" => "BIDON",
            "RLL" => "ROLLOS",
            "UND" => "UNIDADES",
            "SCS" => "SACOS",
            "BLT" => "BULTOS",
            "HUACAL" => "HUACAL",
            "TBS" => "tbs",
            "GRL" => "GRANEL",
            "CNT_20" => "1_20pies",
            "2CNT_20" => "2_20pies",
            "CNT_40" => "1_40pies",
            "STB" => "STB",
            "PALLETE" => "PALLETE",
            "TNQ_ACERO" => "TNQ_ACERO",
            "TNQ_LAMINA" => "TNQ_LAMINA",
        );
    }

    function _Get_CEmpaque2() {

        return array(
            '0' => 'Paquete',
            '4' => 'Bulto',
            '6' => 'Granel liquido',
            '7' => 'CONT de 20 pies',
            '8' => '2 CONT de 20 pies',
            '9' => 'CONT de 40 pies',
            '12' => 'Cilindros',
            '15' => 'Granel solido',
            '17' => 'Varios',
            '18' => 'no aplica',
            '19' => 'Carga estibada',
        );
    }

    function _Get_CEmpaqueXId($id) {
        $empaques = funciones::_Get_CEmpaque();
        return $empaques[$id];
    }

    function _Get_CEmpaqueXId2($id) {
        $empaques = funciones::_Get_CEmpaque2();
        return $empaques[$id];
    }

    function _Get_CNaturaleza() {
        return array(
            '1' => 'Carga Normal',
            '2' => 'Carga Peligrosa',
            '3' => 'Carga Extradimencionada',
            '4' => 'Carga Extrapesada',
        );
    }

    function TraerCiudadCodViejo($ciudadP) {
// var_dump($ciudadP);
        $tamano = strlen($ciudadP);
        $NombreCiudad = "";
        if ($tamano >= 8) {
            if (strpos($ciudadP, "(") === false) {
                $ciudadP = str_replace("-", "", $ciudadP);
                $ciudadP = substr($ciudadP, 0, 4);
                $codD = substr($ciudadP, 0, strlen($ciudadP[0]));
                $codC = substr($ciudadP, strlen($ciudadP[0]), 5);
                if (strlen($codD) == 1)
                    $codD = "0" . $codD;
//var_dump($codD, $codC);
                if ($codD != '00')
                    $CiudadCodigoViejo = Ciudades::model()->findByAttributes(array('codigo_departamento' => $codD, 'codigo' => $codC));
                if ($CiudadCodigoViejo)
                    $NombreCiudad = $CiudadCodigoViejo->ciudad;
//var_dump($CiudadCodigoViejo);
//exit;
            }else {
                $separada = explode("(", $ciudadP);
                $ciudadP = str_replace(")", "", $separada[1]);
// var_dump($ciudadP);
                if (strlen($ciudadP) == 7) {
                    $ciudadP = substr($ciudadP, 0, 4);
                    $codD = substr($ciudadP, 0, 1);
                    $codC = substr($ciudadP, 1, 4);
                } else {
                    $ciudadP = substr($ciudadP, 0, 5);
                    $codD = substr($ciudadP, 0, 2);
                    $codC = substr($ciudadP, 2, 5);
                }
                if (strlen($codD) == 1)
                    $codD = "0" . $codD;
//                var_dump($codD, $codC);
                if ($codD != '00')
                    $CiudadCodigoViejo = Ciudades::model()->findByAttributes(array('codigo_departamento' => $codD, 'codigo' => $codC));
                if ($CiudadCodigoViejo)
                    $NombreCiudad = $CiudadCodigoViejo->ciudad;
// var_dump($CiudadCodigoViejo);
//exit;
            }
        }
        return $NombreCiudad;
    }

    public function _GetDescuentos($valor, $origen, $orden = NULL, $manifiesto = null, $descuView = null) { //No se recibia descuView
        $ciudadRete = Ciudades::model()->findByPk($origen);
        $valorReteica = ($ciudadRete->reteica > 0) ? $ciudadRete->reteica : Ciudades::model()->findByAttributes(array('default' => 1))->reteica;

        if ($orden != NULL) {

            $valor_ica = $valorReteica * $valor / 100;
            $retefuente = $valor * 0.01;

            $ordenproduccion = OrdenProduccion::model()->findBypk($orden);

            if ($ordenproduccion->descuento == 1) {

                if ($ordenproduccion->descuento == 1 and $ordenproduccion->porce_descuento == 0) {
                    $ordenproduccion->porce_descuento = 3.9;
                } else if ($ordenproduccion->descuento == 0) {
                    $ordenproduccion->porce_descuento = 0;
                }

                $descuento_ley = $valor * $ordenproduccion->porce_descuento / 100;
            }
        } else {

            $valor_ica = $valorReteica * $valor / 100;
            $retefuente = 0;
            $descuento_ley = 0;
        }


        return array(
            'rete_fuente' => $retefuente,
            'ica' => $valor_ica,
            'descuento_ley' => $descuento_ley,
            'total' => $valor_ica + $retefuente + $descuento_ley,
        );
    }

    public function estadoManifiestoXorden($orden = null) {
        if ($orden) {
            $modelRemesa = Remesas::model()->findByAttributes(array('orden_produccion' => $orden));
            if ($modelRemesa) {
                $modelManifiesto = Manifiestos::model()->findByAttributes(array('manifiesto' => $modelRemesa->manifiesto));
                if ($modelManifiesto) {
                    return $modelManifiesto->estado;
                }
            }
        }
    }

    public function busarString() {
        
    }

    public function _GetParentezco() {
        return array(
            'Conyugue' => 'Conyugue',
            'Padre' => 'Padre',
            'Madre' => 'Madre',
            'Hermano(a)' => 'Hermano(a)',
            'Hijo(a)' => 'Hijo(a)',
            'Tio(a)' => 'Tio(a)',
            'Abuelo(a)' => 'Abuelo(a)',
            'Amigo(a)' => 'Amigo(a)',
            'Primo' => 'Primo',
            'Cuñado' => 'Cuñado',
            'Suegro' => 'Suegro',
            'Nieto' => 'Nieto',
            'Yerno' => 'Yerno',
            'Nuera' => 'Nuera',
            'sobrino(a)' => 'sobrino(a)',);
    }

    function tresnumeros($n, $last) {
//global $numeros100, $numeros10, $numeros11, $numeros, $numerosX;
        $numeros = array("-", "uno", "dos", "tres", "cuatro", "cinco", "seis", "siete", "ocho", "nueve");
        $numerosX = array("-", "un", "dos", "tres", "cuatro", "cinco", "seis", "siete", "ocho", "nueve");
        $numeros100 = array("-", "ciento", "doscientos", "trecientos", "cuatrocientos", "quinientos", "seicientos", "setecientos", "ochocientos", "novecientos");
        $numeros11 = array("-", "once", "doce", "trece", "catorce", "quince", "dieciseis", "diecisiete", "dieciocho", "diecinueve");
        $numeros10 = array("-", "-", "-", "treinta", "cuarenta", "cincuenta", "sesenta", "setenta", "ochenta", "noventa");
        if ($n == 100)
            return "cien ";
        if ($n == 0)
            return "cero ";
        $r = "";
        $cen = floor($n / 100);
        $dec = floor(($n % 100) / 10);
        $uni = $n % 10;
        if ($cen > 0)
            $r .= $numeros100[$cen] . " ";

        switch ($dec) {
            case 0: $special = 0;
                break;
            case 1: $special = 10;
                break;
            case 2: $special = 20;
                break;
            default: $r .= $numeros10[$dec] . " ";
                $special = 30;
                break;
        }
        if ($uni == 0) {
            if ($special == 30)
                ;
            else if ($special == 20)
                $r .= "veinte ";
            else if ($special == 10)
                $r .= "diez ";
            else if ($special == 0)
                ;
        } else {
            if ($special == 30 && !$last)
                $r .= "y " . $numerosX[$n % 10] . " ";
            else if ($special == 30)
                $r .= "y " . $numeros[$n % 10] . " ";
            else if ($special == 20) {
                if ($uni == 3)
                    $r .= "veintitres ";
                else if (!$last)
                    $r .= "veinti" . $numerosX[$n % 10] . " ";
                else
                    $r .= "veinti" . $numeros[$n % 10] . " ";
            } else if ($special == 10)
                $r .= $numeros11[$n % 10] . " ";
            else if ($special == 0 && !$last)
                $r .= $numerosX[$n % 10] . " ";
            else if ($special == 0)
                $r .= $numeros[$n % 10] . " ";
        }
        return $r;
    }

    function seisnumeros($n, $last) {
        if ($n == 0)
            return "cero ";
        $miles = floor($n / 1000);
        $units = $n % 1000;
        $r = "";
        if ($miles == 1)
            $r .= "mil ";
        else if ($miles > 1)
            $r .= funciones::tresnumeros($miles, false) . "mil ";
        if ($units > 0)
            $r .= funciones::tresnumeros($units, $last);
        return $r;
    }

    function docenumeros($n) {
        if ($n == 0)
            return "cero ";
        $millo = floor($n / 1000000);
        $units = $n % 1000000;
        $r = "";
        if ($millo == 1)
            $r .= "un millon ";
        else if ($millo > 1) {
            $r .= funciones::seisnumeros($millo, false) . "millones ";
        }
        if ($units > 0)
            $r .= funciones::seisnumeros($units, true);
        return $r;
    }

    function convertirNumero($num) {

        $numerito = $num;
        $entero = intval($numerito);
        $decimales = ($numerito - $entero) * 100;
        return round($decimales);
    }

    function NumeroLetra($num) {
        return $rpta = funciones::docenumeros($num) . ' Pesos';
    }

    function NumeroLetra2($num) {
        $aux_rpta = trim(funciones::docenumeros($num));
        $rpta = trim($aux_rpta);
        $array = explode(" ", $rpta);
        $mm = array_pop($array);
        $final = "";
        if (in_array($mm, array('millones', 'millon')))
            $final = "de";
        return $aux_rpta . "" . ( strlen($final) > 0 ? " $final" : "") . ' Pesos';
    }

    function EnviarCorreo($asunto = "", $destinatarios = array(), $cuerpo = "") {
        $numcorreo = rand(0, 1);
// $correos = array('alertascargranel1@gmail.com', 'alertascargranel@gmail.com', 'alertas@cargranel.com');
// $pass = array('carga*2011', 'maxell1011', 'cargranel2010');
        $correos = array('alertas1@cargranel.com', 'alertas2@cargranel.com');
        $pass = array('4LERT42020', 'Cargra2020.');
        Yii::import('application.extensions.phpmailer.JPhpMailer');
        $mail = new JPhpMailer;
        $mail->IsSMTP();
        $mail->SMTPDebug = 0;
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "ssl";
        $mail->Username = $correos[$numcorreo];
        $mail->Password = $pass[$numcorreo];
        $mail->Port = '465';

        $mail->SetFrom('alertas@cargranel.com', 'Alertas SID 2.0');
        $mail->Subject = $asunto;
        $mail->MsgHTML($cuerpo);
        foreach ($destinatarios as $correo) {
            $mail->AddAddress($correo);
        }
        //$mail->AddAddress('juanclama@gmail.com');
        if ($mail->Send())
            return 'true';
        else
            return $mail->ErrorInfo;
    }

    function diferenciaEntreFechas($fecha_principal, $fecha_secundaria, $obtener = 'SEGUNDOS', $redondear = false) {
        $f0 = strtotime($fecha_principal);
        $f1 = strtotime($fecha_secundaria);
        if ($f0 < $f1) {
            $tmp = $f1;
            $f1 = $f0;
            $f0 = $tmp;
        }
        $resultado = ($f0 - $f1);
        switch ($obtener) {
            default: break;
            case "MINUTOS" : $resultado = $resultado / 60;
                break;
            case "HORAS" : $resultado = $resultado / 60 / 60;
                break;
            case "DIAS" : $resultado = $resultado / 60 / 60 / 24;
                break;
            case "SEMANAS" : $resultado = $resultado / 60 / 60 / 24 / 7;
                break;
        }
        if ($redondear)
            $resultado = round($resultado);
            //var_dump($resultado);
        return $resultado;
    }

    public function _SetAuditoria($accion, $id = NULL) {
        $modelAuditorias = new AuditoriaGeneral;
        if ($id)
            $id = ", id = " . $id;
        $modelAuditorias->accion = $accion . $id;
        $modelAuditorias->usuario = Yii::app()->session->get('usuarioCedula');
        $modelAuditorias->fecha = date('Y-m-d H:i:s');
        $modelAuditorias->infoip = funciones::_Get_Ip();
        $modelAuditorias->save();
    }

    public function ValidarOrden($orden) {

        $numeroRemesas = Remesas::model()->findAllByAttributes(array('orden_produccion' => $orden));
        $cont = 0;
        foreach ($numeroRemesas as $remesa) {
            if ($remesa->manifiesto)
                if (Manifiestos::model()->findByAttributes(array('manifiesto' => $remesa->manifiesto))->estado != -1)
                    $cont++;
        }
        $ordenP = OrdenProduccion::model()->findByPk($orden);
        if ($ordenP) {
            return $cont >= $ordenP->cantidad ? false : true;
        }
        return false;
    }

    function comprobar($cadena) {
        $arraySeparada = explode("-", trim($cadena));
        if (count($arraySeparada) == 2) {
            if (is_numeric($arraySeparada[0]) && is_numeric($arraySeparada[1]))
                return true;
        } else
            return false;
    }

    public function _GetCupoXCliente($idCliente, $orden = null) {
        $modelCliente = Clientes::model()->FindByPk($idCliente);
        if ($modelCliente) {
            $nit = $modelCliente->identificacion;
            $codigo = $idCliente;


            $strSQL .= "SELECT saldo FROM   v_cartera_edades WHERE  ( v_cartera_edades.nit = " . $nit . " ) ";
            $strSQL .= "       AND  ( v_cartera_edades.tipo in ('51-1','58') ) ";
            $strSQL .= "       AND ( v_cartera_edades.saldo > 1 ) ";

            $rsSQ = Yii::app()->dbdms->createCommand($strSQL)->queryAll();
            $saldototal = $cupocredito = 0;
            if (count($rsSQ) > 0) {
                foreach ($rsSQ as $data)
                    $saldototal += $data['saldo'];
            }
            $rsSQ2 = Yii::app()->dbdms->createCommand("select cupo_credito from terceros where nit = " . $nit)->queryAll();
            if (count($rsSQ2) > 0) {
                foreach ($rsSQ2 as $data)
                    $cupocredito = $data['cupo_credito'];
            }
            $suma = 0;
            $sql = "";
            $sql .= "SELECT movimiento.numero      AS numero, ";
            $sql .= "       movimiento.valor, ";
            $sql .= "       movimiento.explicacion AS expli, ";
            $sql .= "       terceros.nombres       AS nombre ";
            $sql .= "FROM   dbo.movimiento, ";
            $sql .= "       dbo.terceros ";
            $sql .= "WHERE  terceros.nit = '" . $nit . "' ";
            $sql .= "       AND movimiento.nit = terceros.nit ";
            $sql .= "       AND ";
            $sql .= "(( ";
            $sql .= "movimiento.tipo <> 'Z1'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''' Y <>''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''58' )) ";
            $sql .= "AND ( movimiento.cuenta IN ( '28151001', '41450521', '41450523', '41450525', ";
            $sql .= "                             '41450529', '41450533', '41450539', '41450543', ";
            $sql .= "                             '41450547', '13050501', '13551501', '41450525', ";
            $sql .= "                             '13551501', '42505007', '28151001' ) ) ";
            $sql .= "AND (( movimiento.fec >= {ts '2012-01-01 00:00:00'} )) ";
            $sql .= "ORDER  BY movimiento.fec";

            $ors = Yii::app()->dbdms->createCommand($sql)->queryAll();
            $orden_man = $orden_man2 = $factura2 = $vector = array();
            if (count($ors) > 0) {
                foreach ($ors as $data) {
                    if (substr($data['expli'], 0, 10) != "") {
                        if (funciones::comprobar(substr($data['expli'], 0, 11))) {
                            $orden_man[] = substr($data['expli'], 0, 11);
                            $factura2[] = $data['numero'];
                        }
                    }
                }
            }

            $sql = "select * from (";
            $sql .= "SELECT remesas.manifiesto         AS idManifiestoCarga, ";
            $sql .= "       remesas.orden_produccion   AS orpcc, ";
            $sql .= "       remesas.valor_remesa       AS valor, ";
            $sql .= "       remesas.remitente          AS remitente, ";
            $sql .= "       manifiestos.estado         AS estado, ";
            $sql .= "       fletes.vehiculo            AS placa, ";
            $sql .= "       manifiestos.fecha_creacion AS fechac, ";
            $sql .= "       (select count(df.id) from detalle_facturacion as df where df.remesa=remesas.id) as factura";
            $sql .= "       FROM   remesas, ";
            $sql .= "       manifiestos, ";
            $sql .= "       fletes, ";
            $sql .= "       orden_produccion ";
            $sql .= "WHERE  manifiestos.manifiesto = remesas.manifiesto ";
            $sql .= "       AND remesas.manifiesto = fletes.id_manifiesto ";
            $sql .= "       AND remesas.orden_produccion = orden_produccion.id ";
            if ($orden)
                $sql .= "       AND remesas.orden_produccion <> $orden";
            $sql .= "       AND manifiestos.fecha_creacion > '2018-02-01 00:00:00'";
            $sql .= "       AND orden_produccion.remitente =  '" . $codigo . "' ";
            $sql .= "	) as t1 where t1.factura=0 ORDER BY t1.orpcc DESC";

            $command = Yii::app()->db->createCommand($sql);
            $resultados = $command->queryAll();

            foreach ($resultados as $ors) {
                if (strlen($ors['idManifiestoCarga']) < 7) {
                    $remesa = $ors['orpcc'] . "-" . substr($ors['idManifiestoCarga'], 2, 7);
                } else {
                    $remesa = $ors['orpcc'] . "-" . substr($ors['idManifiestoCarga'], 2, 8);
                }
                $sw = 0;

                if (in_array($remesa, $orden_man))
                    $sw++;
                $placas = array(
                    "SNN742", "SNN741", "SNN739", "SNN738", "SNN737", "SNN728", "SNN727", "SNN726", "SNN724", "SNN723"
                );

                if (($sw == 0) && $ors['estado'] != -1 && $ors['valor'] != "" && !in_array($ors['placa'], $placas)) {
                    $suma +=$ors['valor'];
                }
            }

            $saldototal +=$suma;
            $diferencia = (1 * $cupocredito) - $saldototal;

             //return 222222222; 
            return $diferencia;
        }

        return 0;
    }

    public function getGetcodigoDepartamentoViejo($codigo) {
        $departamentosSeparados = explode('-', $codigo);
        if ($departamentosSeparados[1])
            return $departamentosSeparados[1];
        else
            return $codigo;
    }

    public function getGetcodigoCiudadViejo($codigo) {
        $ciudadesSeparados = explode('-', $codigo);
        $ciudadesSeparados[1] = substr($ciudadesSeparados[1], 0, 3);
        $modelCiudades = new Ciudades();
        $Ciudad = $modelCiudades->findByAttributes(array('codigo' => $ciudadesSeparados[1]));
        if ($Ciudad)
            return $Ciudad->id;
        else
            return $codigo;
    }

    public function _Get_Ip() {
        if ($_SERVER["HTTP_X_FORWARDED_FOR"]) {
            if ($pos = strpos($_SERVER["HTTP_X_FORWARDED_FOR"], " ")) {
                $msg .= ", IP local: " . substr($_SERVER["HTTP_X_FORWARDED_FOR"], 0, $pos) . "<br> - IP Pública: " . substr($_SERVER["HTTP_X_FORWARDED_FOR"], $pos + 1);
                $hostlocal = substr($_SERVER["HTTP_X_FORWARDED_FOR"], $pos + 1);
            } else {
                $msg .= ", ippublica= " . $_SERVER["HTTP_X_FORWARDED_FOR"];
                $hostlocal = $_SERVER["HTTP_X_FORWARDED_FOR"];
            }
        } else {
            $msg .="-ippublica=" . $_SERVER["REMOTE_ADDR"];
            $hostlocal = $_SERVER["REMOTE_ADDR"];
            if ($hostlocal != $_SERVER["REMOTE_ADDR"])
                $msg .= ",  Hostname: " . $hostlocal;
        }
//$hostname = gethostbyaddr($hostlocal);
        $hostname = "";
        if ($hostlocal != $hostname)
            $msg .= ", hostname= " . $hostname;

        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_VIA'])) {
            $ip = $_SERVER['HTTP_VIA'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        } else {
            $ip = "Desconocido";
        }
        $msg .= ", ip Real = " . funciones::get_client_ip();
        return $msg;
    }

    public function clientesSinCargar() {
        $resultados = Yii::app()->db->createCommand('SELECT orden_produccion.id, datediff(now(), fecha_elaboracion) AS dias, fecha_elaboracion, remitente FROM orden_produccion WHERE datediff(now(), fecha_elaboracion) < 365 ORDER BY fecha_elaboracion DESC')->queryAll();
        $modelDiferencia = DiferenciaMeses::model()->findByPk(1);
        $date1 = $modelDiferencia->ultimo_envio;
        $date2 = date('Y-m-d H:i:s');

        $diff = abs(strtotime($date2) - strtotime($date1));
        $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
        if ($days > 0) {
            $array_clientes = array();
            $array_diferencias = array();
            $array_menos = array();
            foreach ($resultados as $registro) {
                if (!in_array($registro["remitente"], $array_clientes) && !in_array($registro["remitente"], $array_menos)) {
                    if ($registro["dias"] >= 61) {
                        $array_clientes[] = $registro["remitente"];
                        $array_diferencias[] = $registro["dias"];
                    } else {
                        $array_menos[] = $registro["remitente"];
                    }
                }
            }

            $cuerpo = "<table border='1'><tr><td>Cliente</td><td>Cantidad de meses sin cargar</td></tr>";
            for ($i = 0; $i <= count($array_clientes); $i++) {
                $modelClientes = Clientes::model()->findByPk($array_clientes[$i]);
                $cuerpo .= "<tr>";
                $cuerpo .= "<td>" . $modelClientes->nombre . "</td><td>" . ceil($array_diferencias[$i] / 30) . "</td></tr>";
            }
            $cuerpo .= "</table>";
            $destinatarios[] = "direccioncomercial@cargranel.com";
            $destinatarios[] = "auxcomercial@cargranel.com";
            $asunto = "Clientes sin cargar minimo 2 meses al " . date('Y-m-d');
            if (funciones::EnviarCorreo($asunto, $destinatarios, $cuerpo)) {
                $modelDiferencia->ultimo_envio = date('Y-m-d H:i:s');
                $modelDiferencia->save();
            }
        }
    }

    public function SoloAlfanum($string) {
        $conservar = '0-9a-z '; // juego de caracteres a conservar
        $regex = sprintf('~[^%s]++~i', preg_quote($conservar, '~')); // case insensitive
        $string = preg_replace($regex, '', $string);
        return $string;
    }

    function sanear_string($string) {

        $string = trim($string);

        $string = str_replace(
                array('á', 'à', 'ä', 'â', 'ª', '�?', 'À', 'Â', 'Ä'), array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'), $string
        );

        $string = str_replace(
                array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'), array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'), $string
        );

        $string = str_replace(
                array('í', 'ì', 'ï', 'î', '�?', 'Ì', '�����������������?', 'Î'), array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'), $string
        );

        $string = str_replace(
                array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'), array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'), $string
        );

        $string = str_replace(
                array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'), array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'), $string
        );

        $string = str_replace(
                array('ñ', 'Ñ', 'ç', 'Ç'), array('n', 'N', 'c', 'C',), $string
        );

//Esta parte se encarga de eliminar cualquier caracter extraño
        $string = str_replace(
                array("\\", "¨", "º", "-", "~",
            "#", "|", "!", "\"",
            "·", "$", "%", "&", "/",
            "(", ")", "?", "'", "¡",
            "¿", "[", "^", "`", "]",
            "+", "}", "{", "¨", "´",
            ">", "< ", ";", ",", ":"), '', $string
        );
        return $string;
    }

    function RandomString($length = 10, $uc = TRUE, $n = False) {
        $source = 'abcdefghijklmnopqrstuvwxyz';
        if ($uc == 1)
            $source .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        if ($n == 1)
            $source .= '1234567890';
        if ($length > 0) {
            $rstr = "";
            $source = str_split($source, 1);
            for ($i = 1; $i <= $length; $i++) {
                mt_srand((double) microtime() * 1000000);
                $num = mt_rand(1, count($source));
                $rstr .= $source[$num - 1];
            }
        }
        return $rstr;
    }

    function Get_nombre_ubicacionCompleto($id_ubicacion, $id_historial = '') {
        $ubicacion = Ubicaciones::model()->findByPk($id_ubicacion);
        if ($ubicacion)
            $lugar = $ubicacion->ubicacion;
        $nombreDep = DepartamentosUbicacion::model()->findByPk($ubicacion->departamento);
        if ($nombreDep) {
            $nombreDep = $nombreDep->departamento;
            $nombre_lugar = "$lugar ($nombreDep)";
        }
        if ($nombre_lugar)
            return $nombre_lugar;
        else {
            if ($id_historial)
                $historial = Historial::model()->FindByPk($id_historial)->ubicacion;
            return $historial;
        }
    }

    public function conversor_segundos($seg_ini) {
        $horas = floor($seg_ini / 3600);
        $minutos = floor(($seg_ini - ($horas * 3600)) / 60);
        $segundos = $seg_ini - ($horas * 3600) - ($minutos * 60);
        $resultado .= $horas > 0 ? $horas . 'h' : '';
        if ($horas > 0)
            $resultado .= $minutos > 0 ? ':' . $minutos . 'm' : ':0m';
        else
            $resultado .= $minutos > 0 ? $minutos . 'm' : ':0m';
//$resultado .= ":".$segundos . 's';
        return $resultado;
    }

    function DiferenciaActualizacion($fecha) {
        $segundos = funciones::diferenciaEntreFechas(date('Y-m-d H:i:s'), $fecha, 'SEGUNDOS');
        $resultado = funciones::conversor_segundos($segundos);
        return $resultado;
    }

    public function ValidarDestinoAnticipos($destino = 0, $anticipo = 0, $flete = 0, $manifiesto = 0, $usuario = 0, $m = false) {
        $ciudadesDestino = array(150, 126, 1010, 13);
        if (in_array($destino, $ciudadesDestino) && ($flete * 0.8) <= $anticipo) {
            $usuario = Usuarios::model()->findByAttributes(array('cedula' => $usuario));
            if ($usuario)
                $usuario = $usuario->usuario;
            $asunto = "Manifiesto $manifiesto excedio anticipo. Destino";
            $cuerpo = $m ? "El manifiesto $manifiesto modificado por $usuario" : "El manifiesto $manifiesto creado por $usuario";
            $cuerpo .=" con destino " . Ciudades::model()->findByPk($destino)->ciudad . ", excedio el 80% del valor del flete ($" . number_format($flete) . ") en el anticipo ($" . number_format($anticipo) . ")";
            $destinatarios[] = "operaciones@cargranel.com";
//$destinatarios[] = "anjubama@gmail.com";
            funciones::EnviarCorreo($asunto, $destinatarios, $cuerpo);
        }
    }

    public function ValidarOrigenAnticipos($origen = 0, $anticipo = 0, $flete = 0, $manifiesto = 0, $usuario = 0, $m = false) {
        $ciudadesOrigen = array(150, 126, 1010, 655);
        if (in_array($origen, $ciudadesOrigen) && ($flete * 0.6) <= $anticipo) {
            $usuario = Usuarios::model()->findByAttributes(array('cedula' => $usuario));
            if ($usuario)
                $usuario = $usuario->usuario;
            $asunto = "Manifiesto $manifiesto excedio anticipo. Origen";
            $cuerpo = $m ? "El manifiesto $manifiesto modificado por $usuario" : "El manifiesto $manifiesto creado por $usuario";
            $cuerpo .=" con origen " . Ciudades::model()->findByPk($origen)->ciudad . ", excedio el 60% del valor del flete ($" . number_format($flete) . ") en el anticipo ($" . number_format($anticipo) . ")";
            $destinatarios[] = "operaciones@cargranel.com";
// $destinatarios[] = "anjubama@gmail.com";
            funciones::EnviarCorreo($asunto, $destinatarios, $cuerpo);
            return TRUE;
        } else
            return FALSE;
    }

    public function EnviarCorreoActualizacionLotes() {
        $clientesHoras = Clientes::model()->findAllByAttributes(array('tipo_web' => 2));
        foreach ($clientesHoras as $Cliente) {
            
            $viajes = Viajes::model()->findAllByAttributes(array('id_cliente' => $Cliente->id), 'estado <> 0');
            /*$criteria = new CDbCriteria;
            $criteria->select = "t.*";
            $criteria->join = " LEFT JOIN historial as h on h.id_viaje = t.id_viaje";
            $criteria->addCondition("h.pendiente=1 and t.id_cliente='$Cliente->id'");
            $viajes = Viajes::model()->findAll($criteria);*/
    

            $cuerpo = "";
            $destinatarios=array();
           //var_dump($viajes);
            $lista_correos = array();

            if($viajes){
                //var_dump($viajes);exit;

                foreach ($viajes as $viaje) {
                    $id_viaje = $viaje->id_viaje;
                    $modelViajes = Viajes::model()->findByPk($id_viaje);
                   
                    $historial = Historial::model()->findByAttributes(array('id_viaje' => $id_viaje, 'pendiente' => 1), array('order' => 'id_historial DESC'));

                    //var_dump($historial);exit;
                        
                    if ($historial) {
                        if (funciones::diferenciaEntreFechas(date('Y-m-d H:i:s'), $modelViajes->fecha_historial, "HORAS") <= 45){
                            
                            $modelOrden = OrdenProduccion::model()->findByPk($modelViajes->orden);
                            $modelSitioPernocte = SitiosPernocte::model()->findBypk($historial->sitio_pernocte);
                            $modelRemesas = Remesas::model()->findByPk($modelViajes->id_remesa);
                            $cuerpo .= '<style>table{border-collapse:collapse;font-size:12pt}table,td,th{border:1px solid gray}</style>';
                            $cuerpo .= '<table border=1><tr><td>Estado</td><td>Ubicacion</td>';

                            if($modelSitioPernocte)
                                $cuerpo.='<td>Sitio Pernocte</td>';

                            $cuerpo.='<td>Orden de servicio</td><td>Contenedor</td><td>Placa</td><td>Manifiesto</td><td>Orden P.</td><td>Conductor</td><td>Ruta</td><td>Cliente</td><td>Mercancia</td><td>Fecha</td><tr></tr>';

                            $cuerpo .= '<tr>';
                            $cuerpo .= '<td>' . EstadosViajes::model()->findByPk($historial->estado)->estado . '</td>';
                            $cuerpo .= '<td>' . funciones::Get_nombre_ubicacionCompleto($historial->id_ubicacion) . '</td>';

                            if($modelSitioPernocte)
                                $cuerpo .= '<td>'.$modelSitioPernocte->descripcion.'</td>';
                            

                            $cuerpo .= '<td>' . $modelOrden->factura . '</td>';
                            $cuerpo .= '<td>';
                            if(($modelRemesas->contenedor1=="" || $modelRemesas->contenedor1=="N/A") && ($modelRemesas->contenedor2=="" || $modelRemesas->contenedor2=="N/A")){
                                $cuerpo.='Carga Suelta';
                            }
                            if(($modelRemesas->contenedor1!="" && $modelRemesas->contenedor1!="N/A")){
                                $cuerpo .= $modelRemesas->contenedor1;
                            } 
                            if(($modelRemesas->contenedor2!="" && $modelRemesas->contenedor2!="N/A")){
                                $cuerpo .= '-'.$modelRemesas->contenedor2;
                            }
                            $cuerpo.='</td>';
                            $cuerpo .= '<td>' . $modelViajes->placa . '</td>';
                            $cuerpo .= '<td>' . $modelViajes->manifiesto . '</td>';
                            $cuerpo .= '<td>' . $modelViajes->orden . '</td>';
                            $cuerpo .= '<td>' . Conductores::TraerConductorXManifiesto($modelViajes->manifiesto) . '</td>';
                            $cuerpo .= '<td>' . Ciudades::model()->findByPk($modelRemesas->origen)->ciudad . " - " . Ciudades::model()->findByPk($modelRemesas->destino)->ciudad . '</td>';
                            $cuerpo .= '<td>' . Clientes::model()->findByPk($modelViajes->id_cliente)->nombre . '</td>';
                            $cuerpo .= '<td>' . $modelRemesas->producto_transportado . '</td>';
                            $cuerpo .= '<td>' . $historial->fecha_reportada . '</td>';
                            $cuerpo .= '</tr>';
                            $cuerpo .= '<tr>';
                            $cuerpo .= '<td><div align="center"><b>SELLO</b></div></td>';
                            $cuerpo .= '<td>' . ($modelOrden->requiere_sello == 1 ? 'Si'  : 'No') . '</td>';
                            $cuerpo .= '<td><div align="center"><b>ESCOLTA</b></div></td>';
                            $cuerpo .= '<td>' . ($modelOrden->requiere_escolta == 1 ? 'Si'  : 'No') . '</td>';
                            $cuerpo .= '</tr><tr>';
                            $cuerpo .= '<tr><td colspan="11"><div align="center"><b>INSTRUCCIONES DE SEGURIDAD</b></div></td></tr>';
                            $cuerpo .= '<tr><td colspan="11"><div align="center">' . $modelOrden->instrucciones_seguridad . '</div></td></tr>';
                            $cuerpo .= '<tr><td colspan="11"><div align="center"><b>OBSERVACIONES</b></div></td></tr>';
                            $cuerpo .= '<tr><td colspan="11"><div align="center">' . $historial->observaciones . '</div></td></tr>';
                            $cuerpo .= '<td>" "</td>';
                            $cuerpo .= '</table>';
                        }
                    }
                        $lista_contactos_x_op = Contactos_por_ordenprod::model()->findAllByAttributes(array('id_ordenprod' => $modelViajes->orden));
                        foreach ($lista_contactos_x_op as $contact_indiv) {
                            $destinatarios[$contact_indiv->id_contacto] = Contactos::model()->findByPk($contact_indiv->id_contacto)->email;
                        }
                }
                //var_dump("aqui");

                if (strlen($cuerpo)) {
                    
                    $asunto = "Resumen de reportes de viajes. " . date('h:i a') . " " . funciones::RandomString(1);

                    $enviosContactos = new EnviosContactos;
                  //  $enviosContactos->id_historial = $historial->id_historial;
                    $enviosContactos->id_viaje = $id_viaje;
                    $enviosContactos->orden = $modelViajes->orden;
                  //  $enviosContactos->mensaje = $historial->observaciones;
                    $enviosContactos->cuerpo = $cuerpo;
                    $enviosContactos->asunto = $asunto;
                    $enviosContactos->destinatarios = implode(",",$destinatarios);
                    $enviosContactos->fecha = date("Y-m-d H:i:s");
                    
                    $return = funciones::EnviarCorreo($asunto, $destinatarios, $cuerpo);
                    //$return = funciones::EnviarCorreo("Cron " . $asunto, array('anjubama@gmail.com', 'sistemas@cargranel.com'), $cuerpo);
                    if ($return == 'true') {
                        Historial::model()->updateAll(array('pendiente' => 0), 'id_viaje =' . $id_viaje);
                        $enviosContactos->estado = 1;
                    } else {
                        $enviosContactos->estado = 0;
                        $enviosContactos->error = $return;
                    }
                    if ($enviosContactos->save()) {
                        var_dump($destinatarios,$modelOrden->id);
                        //exit;
                    }
                }
            } 
        }
    }
    
    
       public function EnviarCorreolotes2() {
        $clientesHoras = Clientes::model()->findAllByAttributes(array('tipo_web' => 2));
        foreach ($clientesHoras as $Cliente) {
            
            $viajes = Viajes::model()->findAllByAttributes(array('id_cliente' => $Cliente->id), 'estado <> 0');
            /*$criteria = new CDbCriteria;
            $criteria->select = "t.*";
            $criteria->join = " LEFT JOIN historial as h on h.id_viaje = t.id_viaje";
            $criteria->addCondition("h.pendiente=1 and t.id_cliente='$Cliente->id'");
            $viajes = Viajes::model()->findAll($criteria);*/
    

            $cuerpo = "";
            $destinatarios=array();
           //var_dump($viajes);
            $lista_correos = array();

            if($viajes){
                
                $cuerpo .= '<table>'
                        . '<tr><td><center><img src="https://ii.ct-stc.com/1/logos/empresas/2014/12/10/07f0d496399f4fddbfd8thumbnail.jpeg" style="width: 200px;align-content: center;height: 100px;" alt="INTEGRAL DE CARGA CARGRANEL S.A" title="INTEGRAL DE CARGA CARGRANEL S.A"></center></td></tr>';

                foreach ($viajes as $viaje) {
                    $id_viaje = $viaje->id_viaje;
                    $modelViajes = Viajes::model()->findByPk($id_viaje);
                   
                    $historial = Historial::model()->findByAttributes(array('id_viaje' => $id_viaje, 'pendiente' => 1), array('order' => 'id_historial DESC'));

                    //var_dump($historial);exit;
                        
                    if ($historial) {
                        if (funciones::diferenciaEntreFechas(date('Y-m-d H:i:s'), $modelViajes->fecha_historial, "HORAS") <= 45){
                            
                            $modelOrden = OrdenProduccion::model()->findByPk($modelViajes->orden);
                            $modelSitioPernocte = SitiosPernocte::model()->findBypk($historial->sitio_pernocte);
                            $modelRemesas = Remesas::model()->findByPk($modelViajes->id_remesa);
                            
                            $cuerpo .= '<tr><td><table style="background: #efeff1;border-radius: 7px;display: inline-block;border-collapse: collapse;" border=1><tr><td colspan="9"><table style="width: 100%;"><tr><td style="width:50%" ><center><h3><strong>Manifiesto :</strong>'.$modelViajes->manifiesto.'</h3></center></td><td><center><h3><strong>Ruta : </strong>'.Ciudades::model()->findByPk($modelRemesas->origen)->ciudad . " - " . Ciudades::model()->findByPk($modelRemesas->destino)->ciudad .'</h3></center></td></tr></table></td></tr>'
                                    . '<tr style="font-size: 11px;font-weight: bold;background-color: #cd8544;text-align: center"><td>Estado</td><td>Ubicacion</td>';

                            $cuerpo.='<td>Orden de servicio</td><td>Placa</td><td>Orden P.</td><td>Conductor</td><td>Cliente</td><td>Mercancia</td><td>Fecha</td><tr></tr>';

                            $cuerpo .= '<tr>';
                            $cuerpo .= '<td>' . EstadosViajes::model()->findByPk($historial->estado)->estado . '</td>';
                            $cuerpo .= '<td>' . funciones::Get_nombre_ubicacionCompleto($historial->id_ubicacion) . '</td>';
           
                            $cuerpo .= '<td>' . $modelOrden->factura . '</td>';
                            $cuerpo .= '<td>' . $modelViajes->placa . '</td>';
                            //$cuerpo .= '<td>' . $modelViajes->manifiesto . '</td>';
                            $cuerpo .= '<td>' . $modelViajes->orden . '</td>';
                            $cuerpo .= '<td>' . Conductores::TraerConductorXManifiesto($modelViajes->manifiesto) . '</td>';
                            //$cuerpo .= '<td>' . Ciudades::model()->findByPk($modelRemesas->origen)->ciudad . " - " . Ciudades::model()->findByPk($modelRemesas->destino)->ciudad . '</td>';
                            $cuerpo .= '<td>' . Clientes::model()->findByPk($modelViajes->id_cliente)->nombre . '</td>';
                            $cuerpo .= '<td>' . $modelRemesas->producto_transportado . '</td>';
                            $cuerpo .= '<td>' . $historial->fecha_reportada . '</td>';
                            $cuerpo .= '</tr>';
                            
                            if($modelSitioPernocte)
                            {
                            $cuerpo .= '<tr><td colspan="9"><div align="center" style="background-color: #cd8544;" ><b>SITIO DE PERNOCTE</b></div></td></tr>';
                            $cuerpo .= '<tr><td colspan="9"><div align="center">' .$modelSitioPernocte->descripcion. '</div></td></tr>';
                                
                            }
                            
                            $cuerpo .= '<tr><td colspan="9"><div align="center" style="background-color: #cd8544;" ><b>INSTRUCCIONES DE SEGURIDAD</b></div></td></tr>';
                            $cuerpo .= '<tr><td colspan="9"><div align="center">' . $modelOrden->instrucciones_seguridad . '</div></td></tr>';
                            $cuerpo .= '<tr ><td colspan="9" style="background-color: #cd8544;"><div align="center"><b>OBSERVACIONES</b></div></td></tr>';
                            $cuerpo .= '<tr><td colspan="9"><div align="center">' . $historial->observaciones . '</div></td></tr>';
                            $cuerpo .= '<tr>';
                            $cuerpo .= '<td colspan="9">'
                                    . '<table style="width: 100%;" ><tr><td style="width:50%"><div align="center"><b>SELLO : '.($modelOrden->requiere_sello == 1 ? 'Si'  : 'No') .'</b></div></td>';
                            $cuerpo .= '<td><div align="center"><b>ESCOLTA : ' . ($modelOrden->requiere_escolta == 1 ? 'Si'  : 'No') . '</b></div></td>';
                            $cuerpo .= '</td></tr>';
                            $cuerpo .= '</table>';
                        }
                    }
                        $lista_contactos_x_op = Contactos_por_ordenprod::model()->findAllByAttributes(array('id_ordenprod' => $modelViajes->orden));
//                        foreach ($lista_contactos_x_op as $contact_indiv) {
//                            $destinatarios[$contact_indiv->id_contacto] = Contactos::model()->findByPk($contact_indiv->id_contacto)->email;
//                        }
                        
                        $destinatarios[]="juanclama@gmail.com";
                        
                }
                
                $cuerpo .="</tr></table></td></tr>";

                if (strlen($cuerpo)) {
                    
                    $asunto = "Resumen de reportes de viajes. " . date('h:i a') . " " . funciones::RandomString(1);

                    $enviosContactos = new EnviosContactos;
                  //  $enviosContactos->id_historial = $historial->id_historial;
                    $enviosContactos->id_viaje = $id_viaje;
                    $enviosContactos->orden = $modelViajes->orden;
                  //  $enviosContactos->mensaje = $historial->observaciones;
                    $enviosContactos->cuerpo = $cuerpo;
                    $enviosContactos->asunto = $asunto;
                    $enviosContactos->destinatarios = implode(",",$destinatarios);
                    $enviosContactos->fecha = date("Y-m-d H:i:s");
                    
                    $return = funciones::EnviarCorreo($asunto, $destinatarios, $cuerpo);
                    //$return = funciones::EnviarCorreo("Cron " . $asunto, array('anjubama@gmail.com', 'sistemas@cargranel.com'), $cuerpo);
//                    if ($return == 'true') {
//                        Historial::model()->updateAll(array('pendiente' => 0), 'id_viaje =' . $id_viaje);
//                        $enviosContactos->estado = 1;
//                    } else {
//                        $enviosContactos->estado = 0;
//                        $enviosContactos->error = $return;
//                    }
//                    if ($enviosContactos->save()) {
//                        var_dump($destinatarios,$modelOrden->id);
//                        //exit;
//                    }
                }
            } 
        }
    }
    

    public function EnviarCorreoActualizacion($id_historial, $id_viaje) {
        $modelHistorial = Historial::model()->findByPk($id_historial);
        $modelSitioPernocte = SitiosPernocte::model()->findBypk($modelHistorial->sitio_pernocte);
        $modelViajes = Viajes::model()->findByPk($id_viaje);
        $modelOrden = OrdenProduccion::model()->findByPk($modelViajes->orden);
        $modelRemesas = Remesas::model()->findByPk($modelViajes->id_remesa);
        $cuerpo = '<style>table{border-collapse:collapse;font-size:12pt}table,td,th{border:1px solid gray}</style>';
        $cuerpo .= '<table border=1><tr><td>Estado</td><td>Ubicación</td>';
        
        if($modelSitioPernocte)
            $cuerpo.='<td>Sitio de Pernocte</td>';

        $cuerpo.='<td>Orden de servicio</td><td>Contenedor</td><td>Vehiculo</td><td>Manifiesto</td><td>Oeden de producción</td><td>Conductor</td><td>Ruta</td><td>Cliente</td><td>Producto</td><tr></tr>';
        $cuerpo .= '<tr>';
        $cuerpo .= '<td>' . EstadosViajes::model()->findByPk($modelHistorial->estado)->estado . '</td>';
        $cuerpo .= '<td>' . funciones::Get_nombre_ubicacionCompleto($modelHistorial->id_ubicacion) . '</td>';

        if($modelSitioPernocte)
            $cuerpo .= '<td>Sitio de Pernocte: '.$modelSitioPernocte->descripcion.'</td>';

        $cuerpo .= '<td>' . $modelOrden->factura . '</td>';
        $cuerpo .= '<td>';
        if(($modelRemesas->contenedor1=="" || $modelRemesas->contenedor1=="N/A") && ($modelRemesas->contenedor2=="" || $modelRemesas->contenedor2=="N/A")){
            $cuerpo.='Carga Suelta';
        }
        if(($modelRemesas->contenedor1!="" && $modelRemesas->contenedor1!="N/A")){
            $cuerpo .= $modelRemesas->contenedor1;
        } 
        if(($modelRemesas->contenedor2!="" && $modelRemesas->contenedor2!="N/A")){
            $cuerpo .= '-'.$modelRemesas->contenedor2;
        }
        $cuerpo.='</td>';
        $cuerpo .= '<td>' . $modelViajes->placa . '</td>';
        $cuerpo .= '<td>' . $modelViajes->manifiesto . '</td>';
        $cuerpo .= '<td>' . $modelViajes->orden . '</td>';
        $cuerpo .= '<td>' . Conductores::TraerConductorXManifiesto($modelViajes->manifiesto) . '</td>';
        $cuerpo .= '<td>' . Ciudades::model()->findByPk($modelRemesas->origen)->ciudad . " - " . Ciudades::model()->findByPk($modelRemesas->destino)->ciudad . '</td>';
        $cuerpo .= '<td>' . Clientes::model()->findByPk($modelViajes->id_cliente)->nombre . '</td>';
        $cuerpo .= '<td>' . $modelRemesas->producto_transportado . '</td>';
        $cuerpo .= '<tr><td colspan="11"><div align="center"><b>INSTRUCCIONES DE SEGURIDAD</b></div></td></tr>';
        $cuerpo .= '<tr><td colspan="11"><div align="center">' . $modelOrden->instrucciones_seguridad . '</div></td></tr>';
        $cuerpo .= '<tr><td colspan=10><div align="center">' . $modelHistorial->observaciones . '</div></td></tr>';
        $cuerpo .= '</tr></table>';

        $asunto = "Actualizacion de la Orden de Servicio: " . $modelOrden->factura . " correspondiente al vehiculo:" . $modelViajes->placa;

        $Cliente = Clientes::model()->findByPk($modelViajes->id_cliente);
        $email = funciones::Get_CorreosContact_OrdenProd($modelOrden->id); //XXXXXXXXXXXXXXXXXXXXXXXXXXXXX
//echo "<pre>";var_dump($email);exit;
        $destinatarios = explode(';', $email);
        $enviosContactos = new EnviosContactos;
        $enviosContactos->id_historial = $id_historial;
        $enviosContactos->id_viaje = $id_viaje;
        $enviosContactos->orden = $modelViajes->orden;
        $enviosContactos->cuerpo = $cuerpo;
        $enviosContactos->mensaje = $modelHistorial->observaciones;
        $enviosContactos->asunto = $asunto;
        $enviosContactos->destinatarios = $email;
        $enviosContactos->fecha = date("Y-m-d H:i:s");
        $return = funciones::EnviarCorreo($asunto, $destinatarios, $cuerpo);
//        if ($return == 'true') {
//            $ar = fopen("/var/www/html/nuevosid/historial_correos/enviados/Inmediato " . date("Y-m-d H:i:s") . $Cliente->nombre . ".txt", "a");
//            fputs($ar, 'Enviado a ' . $email);
//            fputs($ar, "\n");
//            fputs($ar, 'Asunto: ' . $asunto);
//            fputs($ar, "\n");
//            fclose($ar);
//            $enviosContactos->estado = 1;
//        } else {
//            $ar = fopen("/var/www/html/nuevosid/historial_correos/no_enviados/Inmediato " . date("Y-m-d H:i:s") . $Cliente->nombre . ".txt", "a");
//            fputs($ar, 'Enviado a ' . $email);
//            fputs($ar, "\n");
//            fputs($ar, 'Asunto: ' . $asunto);
//            fputs($ar, "\n");
//            fclose($ar);   //
//            $enviosContactos->estado = 0;
//            $enviosContactos->error = $return;
//        }
        $enviosContactos->save();
    }

    public function EnviarCorreofializacion($id_historial, $id_viaje) {
        $ViajesFinalizados = new ViajesFinalizados;
        $ViajesFinalizados->id_viaje = $id_viaje;
        $ViajesFinalizados->estado = 0;
        $ViajesFinalizados->fecha = date('Y-m-d H:i:s');
        $ViajesFinalizados->save();
//        $modelHistorial = Historial::model()->findByPk($id_historial);
//        $modelViajes = Viajes::model()->findByPk($id_viaje);
//        $modelOrden = OrdenProduccion::model()->findByPk($modelViajes->orden);
//        $modelRemesas = Remesas::model()->findByPk($modelViajes->id_remesa);
//        $cuerpo = $asunto = "El manifiesto $modelViajes->manifiesto finalizo el " . date("Y-m-d h:i a", strtotime($modelViajes->fecha_historial)) . "del cliente:" . $modelOrden->quien_paga_flete;
//
//
//        $destinatarios = array('auxfacturacion@cargranel.com');
//        funciones::EnviarCorreo($asunto, $destinatarios, $cuerpo);
    }

    public function EnviarCorreoSeguridad($id_historial, $id_viaje) {
       
        $modelHistorial = Historial::model()->findByPk($id_historial);
        $modelViajes = Viajes::model()->findByPk($id_viaje);
        $modelOrden = OrdenProduccion::model()->findByPk($modelViajes->orden);
        $modelRemesas = Remesas::model()->findByPk($modelViajes->id_remesa);
        $asunto = "Novedad de seguridad del manifiesto $modelViajes->manifiesto  " ;
        $cuerpo = "El manifiesto $modelViajes->manifiesto presenta novedad de seguridad " . date("Y-m-d h:i a", strtotime($modelViajes->fecha_historial)) . "del cliente:" . $modelOrden->quien_paga_flete.$modelHistorial->observaciones ;
        $destinatarios = array('direccioncomercial@cargranel.com', 'gerente@cargranel.com', 'operaciones@cargranel.com', 'operacionesdos@cargranel.com','comercialcuatro@cargranel.com','diacorecolecciones@cargranel.com','comercial@cargranel.com','comercialuno@cargranel.com','comercialdos@cargranel.com','comercialtres@cargranel.com','comercialcinco@cargranel.com');
        funciones::EnviarCorreo($asunto, $destinatarios, $cuerpo);
    }
    
    public function EnviarCorreoVarado($id_historial, $id_viaje) {
        $modelHistorial = Historial::model()->findByPk($id_historial);
        $modelViajes = Viajes::model()->findByPk($id_viaje);
        $modelOrden = OrdenProduccion::model()->findByPk($modelViajes->orden);
        $modelRemesas = Remesas::model()->findByPk($modelViajes->id_remesa);
        $cuerpo = '<style>table{border-collapse:collapse;font-size:12pt}table,td,th{border:1px solid gray}</style>';
        $cuerpo .= '<div align="center"><b><h3>Se reporta vehiculo varado</h3></b></div><br>';
        $cuerpo .= '<table border=1><tr><td>Estado</td><td>Ubicación</td><td>Orden de servicio</td><td>Vehiculo</td><td>Manifiesto</td><td>Oeden de producción</td><td>Conductor</td><td>Ruta</td><td>Cliente</td><td>Producto</td><tr></tr>';
        $cuerpo .= '<tr>';
        $cuerpo .= '<td>' . EstadosViajes::model()->findByPk($modelHistorial->estado)->estado . '</td>';
        $cuerpo .= '<td>' . funciones::Get_nombre_ubicacionCompleto($modelHistorial->id_ubicacion) . '</td>';
        $cuerpo .= '<td>' . $modelOrden->factura . '</td>';
        $cuerpo .= '<td>' . $modelViajes->placa . '</td>';
        $cuerpo .= '<td>' . $modelViajes->manifiesto . '</td>';
        $cuerpo .= '<td>' . $modelViajes->orden . '</td>';
        $cuerpo .= '<td>' . Conductores::TraerConductorXManifiesto($modelViajes->manifiesto) . '</td>';
        $cuerpo .= '<td>' . Ciudades::model()->findByPk($modelRemesas->origen)->ciudad . " - " . Ciudades::model()->findByPk($modelRemesas->destino)->ciudad . '</td>';
        $cuerpo .= '<td>' . Clientes::model()->findByPk($modelViajes->id_cliente)->nombre . '</td>';
        $cuerpo .= '<td>' . $modelRemesas->producto_transportado . '</td>';
        $cuerpo .= '<tr><td colspan=10><div align="center">' . $modelHistorial->observaciones . '</div></td></tr>';
        $cuerpo .= '</tr></table>';

        $asunto = "Vehiculo varado: " . $modelViajes->placa;
        $destinatarios[] = "operaciones@cargranel.com";
        $destinatarios[] = "direccioncomercial@cargranel.com";
        $destinatarios[] = "comercial@cargranel.com";
        $destinatarios[] = "comercialuno@cargranel.com";
        $destinatarios[] = "comercialdos@cargranel.com";
        $destinatarios[] = "comercialtres@cargranel.com";
        $destinatarios[] = "trafico@cargranel.com";
        $destinatarios[] = "gerente@cargranel.com";
        $destinatarios[] = "auxtrafico@cargranel.com";
        $destinatarios[] = "auxseguridad@cargranel.com";
        $destinatarios[] = "auxtraficodos@cargranel.com";
        $destinatarios[] = "operacionesdos@cargranel.com";
        $destinatarios[] = "comercialcinco@cargranel.com";
        $destinatarios[] = "comercialcuatro@cargranel.com";
        $destinatarios[] = "diacorecolecciones@cargranel.com";
//$destinatarios[] = 'anjubama@gmail.com';
        return funciones::EnviarCorreo($asunto, $destinatarios, $cuerpo);
    }
    
    public function EnviarCorreoVaradoMagnum($id_historial, $id_viaje) {
        $modelHistorial = Historial::model()->findByPk($id_historial);
        $modelViajes = Viajes::model()->findByPk($id_viaje);
        $modelOrden = OrdenProduccion::model()->findByPk($modelViajes->orden);
        $modelRemesas = Remesas::model()->findByPk($modelViajes->id_remesa);
        $cuerpo = '<style>table{border-collapse:collapse;font-size:12pt}table,td,th{border:1px solid gray}</style>';
        $cuerpo .= '<div align="center"><b><h3>Se reporta vehiculo varado</h3></b></div><br>';
        $cuerpo .= '<table border=1><tr><td>Estado</td><td>Ubicación</td><td>Orden de servicio</td><td>Vehiculo</td><td>Manifiesto</td><td>Oeden de producción</td><td>Conductor</td><td>Ruta</td><td>Cliente</td><td>Producto</td><tr></tr>';
        $cuerpo .= '<tr>';
        $cuerpo .= '<td>' . EstadosViajes::model()->findByPk($modelHistorial->estado)->estado . '</td>';
        $cuerpo .= '<td>' . funciones::Get_nombre_ubicacionCompleto($modelHistorial->id_ubicacion) . '</td>';
        $cuerpo .= '<td>' . $modelOrden->factura . '</td>';
        $cuerpo .= '<td>' . $modelViajes->placa . '</td>';
        $cuerpo .= '<td>' . $modelViajes->manifiesto . '</td>';
        $cuerpo .= '<td>' . $modelViajes->orden . '</td>';
        $cuerpo .= '<td>' . Conductores::TraerConductorXManifiesto($modelViajes->manifiesto) . '</td>';
        $cuerpo .= '<td>' . Ciudades::model()->findByPk($modelRemesas->origen)->ciudad . " - " . Ciudades::model()->findByPk($modelRemesas->destino)->ciudad . '</td>';
        $cuerpo .= '<td>' . Clientes::model()->findByPk($modelViajes->id_cliente)->nombre . '</td>';
        $cuerpo .= '<td>' . $modelRemesas->producto_transportado . '</td>';
        $cuerpo .= '<tr><td colspan=10><div align="center">' . $modelHistorial->observaciones . '</div></td></tr>';
        $cuerpo .= '</tr></table>';

        $asunto = "Vehiculo varado: " . $modelViajes->placa;
        //$destinatarios[] = "sistemas@cargranel.com";
        $destinatarios[] = "wiherrera@magnum.com.co";
        
//$destinatarios[] = 'anjubama@gmail.com';
        return funciones::EnviarCorreo($asunto, $destinatarios, $cuerpo);
    }
    
     public function EnviarCorreoCambioDescargue($manifiesto,$fecha_cita_descarge) {
        $modelManifiestos = Manifiestos::model()->findByPk($manifiesto);
        
        $cuerpo = '<style>table{border-collapse:collapse;font-size:12pt}table,td,th{border:1px solid gray}</style>';
        $cuerpo .= '<div align="center"><b><h3>Se cambio la fecha y hora de Descarge del manifiesto</h3></b></div><br>';
        $cuerpo .= '<tr>';       
        $cuerpo .= '<div align="center"><b><h3><td>' . $manifiesto. '</td></h3></b></div><br>';
        $cuerpo .= '<div align="center"><b><h3>Fecha y Hora </h3></b></div><br>';
        $cuerpo .= '<div align="center"><b><h3>' . $fecha_cita_descarge. '</h3></b></div><br>';
        $cuerpo .= '<td></td>';
        $cuerpo .= '</tr></table>';
        $asunto = "cambio Descargue: " . $manifiesto;
        $destinatarios[] = "trafico@cargranel.com";
        $destinatarios[] = "trafico4@cargranel.com";
        $destinatarios[] = "trafico2@cargranel.com";
        $destinatarios[] = "trafico5@cargranel.com";
        $destinatarios[] = "auxtrafico@cargranel.com";
        $destinatarios[] = "auxseguridad@cargranel.com";
        $destinatarios[] = "auxtraficodos@cargranel.com";
       // $destinatarios[] = "sistemas@cargranel.com";
        //var_dump($cuerpo,$asunto);exit;
        return funciones::EnviarCorreo($asunto, $destinatarios, $cuerpo);
    }
    
    public function EnviarCorreoCambioDirDes($nueva,$anterior) {
        //var_dump("si entro a la funcoin");exit;
        
        $modelOP = OrdenProduccion::model()->findByPk($nueva);
        
        $cuerpo = '<style>table{border-collapse:collapse;font-size:12pt}table,td,th{border:1px solid gray}</style>';
        $cuerpo .= '<div align="center"><b><h3>Se cambio la direccion de la orden de produccion </h3></b></div><br>';
       // $cuerpo .= '<table border=1><tr><td>Estado</td><td>Ubicación</td><td>Orden de servicio</td><td>Vehiculo</td><td>Manifiesto</td><td>Oeden de producción</td><td>Conductor</td><td>Ruta</td><td>Cliente</td><td>Producto</td><tr></tr>';
        $cuerpo .= '<div align="center"><b><h3>direccin antigua :  '.$anterior.' </h3></b></div><br>';
        $cuerpo .= '<div align="center"><b><h3>direccion nueva : '.$modelOP->direccion_descargue.' </h3></b></div><br>';
        $cuerpo .= '<tr>';       
       
        $cuerpo .= '</tr></table>';
        $asunto = "cambio direccion Descargue: " . $modelOP->id;
        $destinatarios[] = "trafico@cargranel.com";
        $destinatarios[] = "trafico2@cargranel.com";
        $destinatarios[] = "trafico4@cargranel.com";
        $destinatarios[] = "trafico5@cargranel.com";
        $destinatarios[] = "auxtrafico@cargranel.com";
        $destinatarios[] = "auxseguridad@cargranel.com";
        $destinatarios[] = "auxtraficodos@cargranel.com";
       // $destinatarios[] = "sistemas@cargranel.com";

        return funciones::EnviarCorreo($asunto, $destinatarios, $cuerpo);
    }
    
     public function EnviarCorreoAnulado($manifiesto,$motivo) {
        $modelManifiestos = Manifiestos::model()->findByPk($manifiesto);
        
        $cuerpo = '<style>table{border-collapse:collapse;font-size:12pt}table,td,th{border:1px solid gray}</style>';
        $cuerpo .= '<div align="center"><b><h3>El manifiesto fue anulado</h3></b></div><br>';
        $cuerpo .= '<tr>';       
        $cuerpo .= '<div align="center"><b><h3><td>' . $manifiesto. '</td></h3></b></div><br>';
        $cuerpo .= '<div align="center"><b><h3>Motivo</h3></b></div><br>';
        $cuerpo .= '<div align="center"><b><h3>' . $motivo. '</h3></b></div><br>';
        $cuerpo .= '<td></td>';
        $cuerpo .= '</tr></table>';
        $asunto = "Se anulo manifiesto : " . $manifiesto;
        $destinatarios[] = "auxoperaciones@cargranel.com";
        
       // $destinatarios[] = "sistemas@cargranel.com";
        //var_dump($cuerpo,$asunto);exit;
        return funciones::EnviarCorreo($asunto, $destinatarios, $cuerpo);
    }

    public function ClaseGidViajes($fecha, $estado) {
        if ($estado != 8) {
            $diferencia = funciones::diferenciaEntreFechas(date('Y-m-d H:i:s'), $fecha, "HORAS");
            if ($diferencia >= 2)
                return 'red';
            else
                return 'odd';
        } else
            return 'green';
    }

    public function Encrypt_web($string) {//hash que encripta la cadena
        $semilla = substr($string, 0, 2);
        $crypted = crypt(md5($string), md5($semilla));
        return $crypted;
    }

    public function Get_CorreosContact_OrdenProd($id_ordenprod) {  //XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
        //var_dump($id_ordenprod);exit;
        $lista_contactos_x_op = Contactos_por_ordenprod::model()->findAllByAttributes(array('id_ordenprod' => $id_ordenprod));
        $lista_correos = array();
        var_dump($lista_correos);
        foreach ($lista_contactos_x_op as $contact_indiv) {
            $lista_correos[$contact_indiv->id_contacto] = Contactos::model()->findByPk($contact_indiv->id_contacto)->email;
            //var_dump($lista_correos[]);
        }
       
        return implode(';', $lista_correos);
    }

//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

    function _Get_Estados_OrdenProd() { //XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
        return array(
            '1' => 'Abierta',
            '0' => 'Cerrada',
            '2' => 'Cambio de mes',
            '3' => 'Cancelada',
            '4' => 'Incumplida',
        );
    }

    function _Get_Estados_OrdenProdXID($id) {
        $estados = funciones::_Get_Estados_OrdenProd();
        return $estados[$id];
    }

//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

    function Get_fleteProd_manifi2($id_ordenProd) { //XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
        $model_ordenProd = OrdenProduccion::model()->findbyPk($id_ordenProd);
        if (($model_ordenProd->tipoCobro) == 1) {
            $cantidad = $model_ordenProd->cantidad;
            $flete_prod = $model_ordenProd->flete_produccion;
            $peso_en_ton = ($model_ordenProd->peso);
            return ($flete_prod * $peso_en_ton);
        } else {
            return $model_ordenProd->flete_produccion;
        }
    }

    function Get_fleteProd_manifi($id_ordenProd) { //XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
        $model_ordenProd = OrdenProduccion::model()->findbyPk($id_ordenProd);
        if (($model_ordenProd->tipoCobro) == 1) {
            $cantidad = $model_ordenProd->cantidad;
            $flete_prod = $model_ordenProd->flete_produccion;
            $peso_en_ton = ($model_ordenProd->peso);
            return "<div id='FleteProd_$id_ordenProd'>" . ($flete_prod * $peso_en_ton) . "</div>";
        } else {
            return "<div id='FleteProd_$id_ordenProd'>" . $model_ordenProd->flete_produccion . "</div>";
        }
    }

//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX	

    function Verificar_facturacion_manifiestos($fecha_ini, $fecha_fin) {  //XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
        echo "La funcion se empezo a ejecutarse en el momento: " . date("Y-m-d H:i:s") . " \n"; //XXXXXXXXXXXXXXXXXXXXXXXXXXXXX  <br />    \n
        echo "Todo se guardara en el archivo >> /var/www/html/nuevosid/historial_demonio/Registro: " . date("Y-m-d H:i:s") . ".txt \n"; //XXXXXXXXXXXX  						
        $ar = fopen("/var/www/html/nuevosid/historial_demonio/Registro: " . date("Y-m-d H:i:s") . ".txt", "a+r"); //XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
        fputs($ar, "La funcion se empezo a ejecutarse en el momento: " . date("Y-m-d H:i:s") . " \n\n"); //XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX	

        $hostname = "192.168.10.7";
        $database = "cargra";
        $username = "sa";
        $password = "c4rgr4n3l2016.";

        try {
            $enlace = mssql_connect($hostname, $username, $password);
        } catch (Exception $e) {
            echo "Error conectandose al Servidor " . "\n";
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
        try {
            $mb = mssql_select_db($database, $enlace);
        } catch (Exception $e) {
            echo "Error Seleccionando la Base de Datos" . "\n";
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }

        $query = "";
        $query .= "SELECT v_imputacion_real.numero, v_imputacion_real.explicacion ";
        $query .= "FROM dbo.terceros terceros, dbo.v_imputacion_real v_imputacion_real ";
        $query .= "WHERE terceros.nit = v_imputacion_real.nit AND ( (v_imputacion_real.tipo='51') ";
        $query .= "AND (v_imputacion_real.cuenta Not Like '13%') ";
        $query .= "AND (v_imputacion_real.fec between '" . $fecha_ini . "'  ";
        $query .= "AND                                '" . $fecha_fin . "'  )  ) ";
        $query .= "order by v_imputacion_real.fec ";
        $query_done = mssql_query($query, $enlace);

        fputs($ar, " EJECUTO EL QUERY \n\n"); //XXXXXXXXXXXXXX
        echo " EJECUTO EL QUERY \n\n"; //XXXXXXXXXXXXXXXXXXXXX

        if (mssql_num_rows($query_done)) {
            $manifiesto_anterior = '0';
            for ($i = 0; $i < mssql_num_rows($query_done); ++$i) {
                $numero_factura = mssql_result($query_done, $i, 'numero');
                $explicacion_factura = mssql_result($query_done, $i, 'explicacion');
                $manifiesto = "" . substr($explicacion_factura, 6, 5);
                $ordenprod = "" . substr($explicacion_factura, 0, 5);
                fputs($ar, "Explicacion: " . $explicacion_factura . "      y numero: " . $numero_factura . "\n\n"); //XXXXXXXXXXXXXX
                echo "Explicacion: " . $explicacion_factura . "      y numero: " . $numero_factura . "\n\n"; //XXXXXXXXXXXXXXXXXXXXX
                if ($manifiesto_anterior != $manifiesto) {
                    $criteria = new CDbCriteria;
                    $criteria->condition = "manifiesto LIKE '%" . $manifiesto . "'";
                    $model_manifiesto = Manifiestos::model()->find($criteria);

                    if ($model_manifiesto != null) {
                        fputs($ar, "Se encontro el manifiesto: " . $model_manifiesto->manifiesto . "\n\n"); //XXXXXXXXXXXXXXXXXX
                        echo "Se encontro el manifiesto: " . $model_manifiesto->manifiesto . "\n\n"; //XXXXXXXXXXXXXXXXXXXXXXXXX						
                        $model_manifiesto->facturado = 'Si';
                        if ($model_manifiesto->save()) {
                            fputs($ar, "Se cambio el estado a Facturado \n\n"); //XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
                            echo "Se cambio el estado a Facturado \n\n"; //XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX 
                        }
                    }
                }
                $manifiesto_anterior = $manifiesto;

                $criteria = new CDbCriteria;
                $criteria->condition = "id LIKE '" . $ordenprod . "'";
                $model_ordenprod = OrdenProduccion::model()->find($criteria);
                if ($model_ordenprod != null) {
                    fputs($ar, "Encontro la ordenprod: " . $model_ordenprod->id . "\n\n"); //XXXXXXXXXXXXXXXXXX
                    echo "Encontro la ordenprod: " . $model_ordenprod->id . "\n\n"; //XXXXXXXXXXXXXXXXXXXXXXXXX
                    $criteria2 = new CDbCriteria;
                    $criteria2->condition = "id_ordenprod = '" . $model_ordenprod->id . "' AND num_factura = '" . $numero_factura . "' ";
                    $model_ordenprod_factura = OrdenProd_factura::model()->find($criteria2);
                    if ($model_ordenprod_factura == null) {
                        fputs($ar, "ordenprod: " . $model_ordenprod->id . " y numero: " . $numero_factura . " Fueron agregadas porque no estaban repetidas \n\n"); //XXXXXXX
                        echo "ordenprod: " . $model_ordenprod->id . " y numero: " . $numero_factura . " Fueron agregadas porque no estaban repetidas \n\n"; //XXXXXXXXXXXXXX								
                        $model_ordenprod_factura = new OrdenProd_factura;
                        $model_ordenprod_factura->id_ordenprod = $ordenprod;
                        $model_ordenprod_factura->num_factura = $numero_factura;
                        $model_ordenprod_factura->save();
                    }
                }
            }
        }

        if (mssql_close($enlace)) {
            fputs($ar, "Se cerro la coneccion \n\n"); //XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
            echo "Se cerro la coneccion \n\n"; //XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX			
        }
        fputs($ar, "La funcion termino de ejecutarse en el momento: " . date("Y-m-d H:i:s") . " \n");
        fclose($ar);
        echo "La funcion termino de ejecutarse en el momento: " . date("Y-m-d H:i:s") . " \n\n\n\n";   //<br /><br />    \n\n\n\n	
    }

//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

    function Get_cant_viajes_escol($id_ordenProd) { //XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
        $model_ordenProd = OrdenProduccion::model()->findbyPk($id_ordenProd);
        $list_remesas = Remesas::model()->findAllByAttributes(array('orden_produccion' => $model_ordenProd->id));
        if ($list_remesas) {
            $cant_viajes_escol = 0;
            foreach ($list_remesas as $remesa) {
                $model_manifiestos = Manifiestos::model()->findByAttributes(array('manifiesto' => $remesa->manifiesto));
                if (($model_manifiestos->asociar_escolta) == true) {
                    $cant_viajes_escol = $cant_viajes_escol + 1;
                }
            }
            return $cant_viajes_escol;
        } else {
            return 0;
        }
    }

//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

    function Get_ordenprod_allescol() { //XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX		
        $list_ordenprod = OrdenProduccion::model()->findAllByAttributes(array('requiere_escolta' => '1'));
        $i = 1;
        $lista_id = '0';
        foreach ($list_ordenprod as $ordenprod) {
            if (($ordenprod->cantidad) != (funciones::Get_cant_viajes_escol($ordenprod->id))) {
                if ($i == 1) {
                    $lista_id = $ordenprod->id;
                    $i = 0;
                } else {
                    $lista_id = $lista_id . ',' . ($ordenprod->id);
                }
            }
        }
        return $lista_id;
    }

//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

    public function GET_mes_informe_manif() {  //XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
        return array(
            '0' => 'Todos los Meses',
            '1' => 'Enero',
            '2' => 'Febrero',
            '3' => 'Marzo',
            '4' => 'Abril',
            '5' => 'Mayo',
            '6' => 'Junio',
            '7' => 'Julio',
            '8' => 'Agosto',
            '9' => 'Septiembre',
            '10' => 'Octubre',
            '11' => 'Nobiembre',
            '12' => 'Diciembre',
        );
    }

//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX	

    public function GET_ano_informe_manif() {  //XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
        $ano_now = (int) (date('Y'));
        return array(
            $ano_now => $ano_now,
            ($ano_now - 1) => ($ano_now - 1),
            ($ano_now - 2) => ($ano_now - 2),
            ($ano_now - 3) => ($ano_now - 3),
            ($ano_now - 4) => ($ano_now - 4),
            ($ano_now - 5) => ($ano_now - 5),
            ($ano_now - 6) => ($ano_now - 6),
        );
    }

//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX	

    public function GET_informe_manifiestos_1($model) {  //XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
        var_dump($model);
            $this->widget('ext.EExcelView.EExcelView', array(
            'id' => 'informe-grid-Exel',
            'dataProvider' => $model->search_info($model->informe_tipoInforme, $model->informe_restric_tiempo, $model->informe_ano, $model->informe_mes, $model->informe_fecha_inicial, $model->informe_fecha_final),
            'grid_mode' => 'export',
            'filename' => 'Informe',
            'columns' => array(
                array(
                    'header' => 'Numero Manifiesto',
                    'name' => 'manifiesto',
                    'htmlOptions' => array('width' => '100px', 'style' => 'text-align: center'),
                ),
                array(
                    'header' => 'Numero de Orden Produccion',
                    'value' => 'Manifiestos::GET_ordenprod($data->manifiesto)->id',
                    'htmlOptions' => array('width' => '100px', 'style' => 'text-align: center'),
                ),
                array(
                    'header' => 'Celular Conductor',
                    'value' => 'Manifiestos::GET_conductor($data->manifiesto)->celular',
                    'htmlOptions' => array('width' => '100px', 'style' => 'text-align: center'),
                ),
                array(
                    'header' => 'Nombre Conductor',
                    'value' => 'Manifiestos::GET_conductor($data->manifiesto)->nombres',
                    'htmlOptions' => array('width' => '100px', 'style' => 'text-align: center'),
                ),
                array(
                    'header' => 'Valor del Flete',
                    'name' => 'valor_flete',
                    'htmlOptions' => array('width' => '100px', 'style' => 'text-align: center'),
                ),
                array(
                    'header' => 'Fecha de Elaboracion',
                    'name' => 'fecha_creacion',
                    'htmlOptions' => array('width' => '100px', 'style' => 'text-align: center'),
                ),
                array(
                    'header' => 'Usuario',
                    'value' => 'Usuarios::model()->findByattributes(array("cedula"=>$data->creador))->nombre',
                    'htmlOptions' => array('width' => '100px', 'style' => 'text-align: center'),
                ),
            ),
        ));
    }

//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX	

    public function GET_informe_manifiestos_3($dataProvider) {  //XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX	
        $this->widget('ext.EExcelView.EExcelView', array(
            'id' => 'informe-grid-Exel',
            'dataProvider' => $dataProvider,
            'grid_mode' => 'export',
            'filename' => 'Informe',
            'columns' => array(
                array(
                    'name' => 'Numero Manifiesto',
                    'value' => '$data[Numero_Manifiesto]',
                ),
                'Regional',
                'Origen',
                'Destino',
                array(
                    'name' => 'Comercial Asignado',
                    'value' => '$data[Com_Asigna]=="Sin Comercial"?"Sin Comercial":Usuarios::model()->findByPk($data[Com_Asigna])->nombre',
                ),
                'Cliente',
                'Peso',
                array(
                    'name' => 'Fecha de Elaboracion de la OP',
                    'value' => '$data[Fecha_Elabora_OP]',
                ),
                array(
                    'name' => 'O.P.',
                    'value' => '$data[OrdenProd]',
                ),
                array(
                    'name' => 'Fecha de Despacho',
                    'value' => '$data[Fecha_Despacho]',
                ),
                'Placa',
                array(
                    'name' => 'Cedula Conductor',
                    'value' => '$data[Cedu_Conduc]',
                ),
                array(
                    'name' => 'Flete de Factura',
                    'value' => '$data[Flete_Factura]',
                ),
                array(
                    'name' => 'Flete Produccion',
                    'value' => '$data[Flete_Product]',
                ),
                'Utilidad',
                'Intermediacion',
                array(
                    'name' => 'Otros Costos Facturados',
                    'value' => '$data[Otros_Costos_Fact]',
                ),
                array(
                    'name' => 'Otros Costos Pagados',
                    'value' => '$data[Otros_Costos_Pag]',
                ),
            ),
        ));
    }

    public function _Get_pesos($id = null) {
        $pesos = array(
            1 => 'KILOGRAMOS',
        );
        return $id ? $pesos[$id] : $pesos;
    }

    function validar_fecha_documentos($fecha_documento) {
        $mes_documento = date('Ym', strtotime($fecha_documento)) * 1;
        $mes_actual = date('Ym') * 1;
        if (($mes_documento = $mes_actual)) {
            return true;
        } else
            return false;
    }

    function GetAttributesNotNull($objet) {
        foreach ($objet as $key => $o) {
            if ($o != NULL)
                $return[$key] = $o;
        }
        return $return;
    }

    function GetBancosNumCheque() {
        $rangocheque = Cheques::model()->findAllByAttributes(array("oficina" => $_SESSION['oficina']), array("order" => 'id desc'));
        $bancos = array();
        if ($rangocheque) {
            foreach ($rangocheque as $data) {
                if (($data->rango_final - $data->rango_actual) > 0)
                    $bancos[$data->banco] = ($data->rango_actual + 1) . " - " . BancoOficina::model()->findByAttributes(array('banco' => $data->banco))->descripcion;
            }
        }

        return $bancos;
    }

}

?>
