<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\OrdenTrabajo;
use Maatwebsite\Excel\Concerns\FromArray;
use Illuminate\Contracts\Support\Responsable;//para no usar el create dpwloan en constructor
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Events\AfterSheet;

class UsuariosExport implements FromArray, ShouldAutoSize, WithHeadings, WithTitle, WithEvents, Responsable
{
    use Exportable;   

    protected $variables, $rows, $columns, $valores;
    private $fileName = '';
    private $headers = [
        'Content-Type' => 'text/xlsx',
    ];

    public function __construct($array,$valores)    
    {
        $this->array = $array;
        $this->valores = $valores;
        $this->fileName = $valores['nom_archivo'].".xlsx";
    }

    public function array(): array
    {
        $headings = true;
        $registros = $this->array; 
     
       
        $this->rows = sizeof($registros);
        if($this->rows == 0){
            return $registros;
        }
        
        $array =  (array) $registros[0];
        
        $this->columns = count($array) - 1;
        return $registros;
    }

    public function headings(): array
    {
        return [                
            //'N°',
            'Nombres',
            'Apellidos',
            'Email',
            'Ocupacion',
            'Puesto actual',
            'Última visita',
            'Estado',
            'F.Registro',
        ];
    }

    public function title(): string
    {
        return $this->valores['nombre_hoja'];
    }

    public function registerEvents(): array
    {    
        //ver https://phpspreadsheet.readthedocs.io/en/latest/topics/recipes/#formatting-cells
        return [          
            AfterSheet::class    => function(AfterSheet $event) {
                
                $max_column = substr('ABCDEFGHIJkLMNOPQRSTUVWXYZ',  ($this->columns), 1); //modificar aqui
                                        
                $rows = (string) $this->rows+1 ;
                //$columns = (string) $this->columns ;
                $cellRangeHeader = 'A1:' . $max_column . '1'; // All headers
                $cellRangeBody = 'A2:' . $max_column . $rows; // All BODY

                /* $event->sheet->insertNewColumnBefore('A', 1);
                $event->sheet->setCellValue('A1','Orden'); 
                for($i = 2 ; $i <= $rows ;$i++){
                    $event->sheet->setCellValue('A'.$i,($i-1)); 
                } */
                
                //header
                $styleArrayHeader = [
                    'font' => [
                        'bold' => true,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'black'],                           
                        ],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                        'rotation' => 5,
                        'startColor' => [
                            'argb' => '2a0632',
                        ],
                        'endColor' => [
                            'argb' => 'b887cc',
                        ],
                    ],
                ];                
                $event->sheet->getStyle($cellRangeHeader)->applyFromArray($styleArrayHeader);
                
               //body
                $styleArrayBody = [                   
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'black'],                           
                        ],
                    ],                    
                ];                
                $event->sheet->getStyle($cellRangeBody)->applyFromArray($styleArrayBody);

                $event->sheet->getTabColor()->setRGB('FF0000');
               
                
                // TITULO
                //poner el titulo o la cabecera del documento
                
                $event->sheet->insertNewRowBefore(1, 3);

                $event->sheet->mergeCells("A2:".$max_column."2"); 
                $event->sheet->setCellValue('A2',$this->valores['titulo']);
               
                $styleArrayTitulo = [                   
                    'font' => [
                        'size' => 20,
                    ],   
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],                 
                ];                
                $event->sheet->getStyle("A2:".$max_column."2")->applyFromArray($styleArrayTitulo);

            },
        ];
    }
}
