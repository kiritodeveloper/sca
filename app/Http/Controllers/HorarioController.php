<?php

namespace App\Http\Controllers;


use App\models\HorariosModels;
use App\models\PersonalModels;
use App\models\LogsistemaModels;
use App\models\ConfiguracionModels;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use DB;
use Input;
use Illuminate\Support\Facades\Response;
use Entrust;

class HorarioController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


     public function index($id_persona)
    {
        $lactancia  =   0;
        $periodo    =   0;
        $data_horarios =  HorariosModels::GetHorario($id_persona);
        $data_personal =  PersonalModels::listar($id_persona);
        $data_lactancias =  HorariosModels::lactancia($id_persona);


    foreach ($data_lactancias as $data_lactancia) {
         $lactancia     =   $data_lactancia->activo;
         $periodo       =   $data_lactancia->periodo;
    }




        if (count($data_horarios)==0){
            return view('personal.horario.add',['id_persona'=>$id_persona,'genero'=>$data_personal->idGenero,'lactancia'=>$lactancia,'periodo'=>$periodo]);

        }


        return view('personal.horario.editar',['data_horarios' =>$data_horarios, 'id_persona'=>$id_persona,'genero'=>$data_personal->idGenero,'lactancia'=>$lactancia,'periodo'=>$periodo]);

    }






    public function store(Request $request)
    {

        $this->validate($request, [

            'inicio_asignacion' => 'required|date_format:"d-m-Y"',
            'fin_asignacion' => 'required|date_format:"d-m-Y"',
        ]);

        $domingo_entrada             =   trim($request->input("domingo_entrada"));
        $domingo_salida              =   trim($request->input("domingo_salida"));
        $lunes_entrada               =   trim($request->input("lunes_entrada"));
        $lunes_salida                =   trim($request->input("lunes_salida"));
        $martes_entrada              =   trim($request->input("martes_entrada"));
        $martes_salida               =   trim($request->input("martes_salida"));
        $miercoles_entrada           =   trim($request->input("miercoles_entrada"));
        $miercoles_salida            =   trim($request->input("miercoles_salida"));
        $jueves_entrada              =   trim($request->input("jueves_entrada"));
        $jueves_salida               =   trim($request->input("jueves_salida"));
        $viernes_entrada             =   trim($request->input("viernes_entrada"));
        $viernes_salida              =   trim($request->input("viernes_salida"));
        $sabado_entrada              =   trim($request->input("sabado_entrada"));
        $sabado_salida               =   trim($request->input("sabado_salida"));
        $id_personal                 =   trim($request->input("id_personal"));
        $lactancia                   =   $request->input("lactancia");


        if($lactancia =='1')
        {
             $turno   =   $request->input("turno");
        }
        else
        {
            $turno   =   NULL;
        }

        $inicio_asignacion           =   date('Y-m-d', strtotime($request->input("inicio_asignacion")));
        $fin_asignacion              =   date('Y-m-d', strtotime($request->input("fin_asignacion")));





        $data_configuracion=ConfiguracionModels::listar();
        $tiempo_gracia=$data_configuracion->tiempo_gracia;







        HorariosModels::insertar($id_personal,$domingo_entrada,$domingo_salida,$lunes_entrada, $lunes_salida, $martes_entrada,$martes_salida,$miercoles_entrada,$miercoles_salida, $jueves_entrada,$jueves_salida,$viernes_entrada, $viernes_salida, $sabado_entrada, $sabado_salida,$inicio_asignacion,$fin_asignacion,$lactancia,$tiempo_gracia,$turno);

        LogsistemaModels::insertar('HORARIO','INSERT','USER: '.$id_personal);
        $request->session()->flash('alert-success', 'Carga horaria almacenada con exito!!');

        return redirect('horario/'.$id_personal);
    }



    public function store_editar(Request $request)
    {

        $this->validate($request, [

            'inicio_asignacion' => 'required|date_format:"d-m-Y"',
            'fin_asignacion' => 'required|date_format:"d-m-Y"',
        ]);
        $domingo_entrada             =   trim($request->input("domingo_entrada"));
        $domingo_salida              =   trim($request->input("domingo_salida"));
        $lunes_entrada               =   trim($request->input("lunes_entrada"));
        $lunes_salida                =   trim($request->input("lunes_salida"));
        $martes_entrada              =   trim($request->input("martes_entrada"));
        $martes_salida               =   trim($request->input("martes_salida"));
        $miercoles_entrada           =   trim($request->input("miercoles_entrada"));
        $miercoles_salida            =   trim($request->input("miercoles_salida"));
        $jueves_entrada              =   trim($request->input("jueves_entrada"));
        $jueves_salida               =   trim($request->input("jueves_salida"));
        $viernes_entrada             =   trim($request->input("viernes_entrada"));
        $viernes_salida              =   trim($request->input("viernes_salida"));
        $sabado_entrada              =   trim($request->input("sabado_entrada"));
        $sabado_salida               =   trim($request->input("sabado_salida"));
        $id_personal                 =   trim($request->input("id_personal"));
        $lactancia                   =   $request->input("lactancia");
       

        if($lactancia =='1')
        {
             $turno   =   $request->input("turno");
        }
        else
        {
            $turno   =   NULL;
        }

        $inicio_asignacion           =   date('Y-m-d', strtotime($request->input("inicio_asignacion")));
        $fin_asignacion              =   date('Y-m-d', strtotime($request->input("fin_asignacion")));



        $data_configuracion=ConfiguracionModels::listar();

        if(count($data_configuracion)>0){
            $tiempo_gracia=$data_configuracion->tiempo_gracia;

        }
        else
        {
            $tiempo_gracia='0';
        }



        HorariosModels::editar($id_personal,$domingo_entrada,$domingo_salida,$lunes_entrada, $lunes_salida, $martes_entrada,$martes_salida,$miercoles_entrada,$miercoles_salida, $jueves_entrada,$jueves_salida,$viernes_entrada, $viernes_salida, $sabado_entrada, $sabado_salida,$inicio_asignacion,$fin_asignacion,$lactancia,$tiempo_gracia,$turno);
        LogsistemaModels::insertar('HORARIO','EDIT','USER: '.$id_personal);

        $request->session()->flash('alert-success', 'Carga horaria almacenada con exito!!');

        return redirect('horario/'.$id_personal);
    }


}
