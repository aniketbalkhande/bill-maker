<?php
require('fpdf17/fpdf.php');
$con=mysqli_connect('localhost','root','');
mysqli_select_db($con,'bill_db');


class PDF extends FPDF {
	function Header(){
		$this->SetFont('Arial','B',15);
		
		//dummy cell to put logo
		//$this->Cell(12,0,'',0,0);
		//is equivalent to:
		$this->Cell(12);
		
		//put logo
		// $this->Image('logo-small.png',10,10,10);
		
		$this->Cell(200,10,'Mahalakhyami Oil Showroom, Gokulnagar, Nanded -431602',0,1);
		
		//dummy cell to give line spacing
		//$this->Cell(0,5,'',0,1);
		//is equivalent to:
		$this->Ln(5);
		
		$this->SetFont('Arial','B',12);
		
		$this->SetFillColor(180,180,255);
        $this->SetDrawColor(180,180,255);
        
        
		$this->Cell(20,10,'SI',1,0,'',true);
		$this->Cell(79,10,'Description',0,0,'',true);
		$this->Cell(30,10,'Qty',1,0,'',true);
        $this->Cell(30,10,'Price',1,0,'',true);
        $this->Cell(30,10,'Amount',1,1,'',true);
		
	}
	function Footer(){
		//add table's bottom line
		$this->Cell(190,0,'','T',1,'',true);
		
		//Go to 1.5 cm from bottom
		$this->SetY(-15);
				
		$this->SetFont('Arial','',9);
		
		//width = 0 means the cell is extended up to the right margin
		$this->Cell(0,10,'Page '.$this->PageNo()." / {pages}",0,0,'C');
	}
}


//A4 width : 219mm
//default margin : 10mm each side
//writable horizontal : 219-(10*2)=189mm

$pdf = new PDF('P','mm','A4'); //use new class

//define new alias for total page numbers
$pdf->AliasNbPages('{pages}');

$pdf->SetAutoPageBreak(true,15);
$pdf->AddPage();

$pdf->SetFont('Arial','',12);
$pdf->SetDrawColor(1,1,1);

$query=mysqli_query($con,"select * from bill");
    while($data=mysqli_fetch_array($query))
    {
	    $pdf->Cell(20,5,$data['SI'],'LR',0,1);
	
    	$pdf->Cell(79,5,$data['description'],'LR',0,1);

    	$pdf->Cell(30,5,$data['qty'],'LR',0,1);
        $pdf->Cell(30,5,$data['price'],'LR',0,1);
        $pdf->Cell(30,5,$data['qty']*$data['price'],'LR',1,1);
    
    }

    // $total = 0;
    // while($data=mysqli_fetch_array($query))
    // {
    //     $total = $total + $data['qty']*$data['price'];
    // }

    $total = 0;
    for($i=0; $row = mysqli_fetch_array($query); $i++)
    {
        $total = $total + $row['qty'] * $row['price'];
    }


    $pdf->Cell(129,5,'',1,0);
    
    $pdf->Cell(25,5,"Total:",'T',0, 0);
    
    $pdf->Cell(30,5,$total,'T',0,0);
    $pdf->Cell(5,5,"/-",'TR',1,0);
    









    $pdf->Output();
?>