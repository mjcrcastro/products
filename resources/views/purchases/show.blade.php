<!DOCTYPE html> <html> 
    <head> 		
        <meta charset="utf-8" /> 		
        <title>Compra</title>  		 	
    </head>  	<body> 		
        <div class="invoice-box"> 			
            <table cellpadding="0" cellspacing="0"> 				
                <tr class="top"> 					
                    <td colspan="4"> 						
                        <table> 							
                            <tr> 								
                                <td class="title">
                                </td>
                            <ol id="breadCrumbs" class="breadcrumb mb-4">
                                <li class="breadcrumb-item"><a href="index.html">Inicio</a></li>
                                <li class="breadcrumb-item"><a href="./">Listado de Compras</a></li>
                                <li class="breadcrumb-item active">Factura de Compra {{ $purchase->purchase_invoice_number  }}</li> 
                            </ol>
                            <td> 
                                FACTURA : {{ $purchase->purchase_invoice_number  }}<br /> 	
                                FECHA : {{ $purchase->purchase_date  }} <br /> 									
                                <br /> 	
                            </td> 							
                </tr> 						
            </table> 					
        </td> 				
    </tr>  				
    <tr class="information"> 					
        <td colspan="4"> 						
            <table> 							
                <tr> 								
                    <td> 									
                <b>Proveedor:</b>&nbsp{{ $purchase->provider->name }}
                        <br /> 
                        <b>Direccion:</b>&nbsp {{ $purchase->provider->address }}
                        <br /> 									
                        <b> Whatssapp:&nbsp</b>{{ $purchase->provider->whatsapp }} 								 
                    </td>  								
                    <td> 									
                        <b>Comprador:</b> {{ $purchase->buyer->buyer_name  }}
                        <br /> 									
                        <br /> 									
                        <br /> 								
                    </td> 							
                </tr> 						
            </table> 					
        </td> 				
    </tr>  				
    <tr class="heading"> 					
        <td>Artículo</td>  					
        <td align="right">Cantidad</td> 					 					
        <td align="right">Costo</td>  					
        <td align="right">Total</td> 				
    </tr>  
    @if($purchase->purchaseDetails->count())
    @foreach($purchase->purchaseDetails as $purchaseDetail)
    <tr class="item"> 
        <td align='left'>  {{ $purchaseDetail->product->description }}</td> 
        <td align='right'> {{ $purchaseDetail->amount }}</td> 
        <td align='right'> {{ number_format ($purchaseDetail->cost,2) }}</td> 
        <td align='right'> {{ number_format($purchaseDetail->amount*$purchaseDetail->cost,2) }}</td> 
    </tr> 
    @endforeach
    @endif
    <tr class="total"> 					
        <td>

        </td> 					
        <td>

        </td> 					
        <td>

        </td>  					
        <td align="right">
            Total General: C$ {{ number_format($purchaseTotal,2) }} 
        </td> 				
    </tr> 			
</table> 
<button id="printPageButton" class='btn btn-block text-nowrap btn-primary' onClick="window.print();">Imprimir</button>
{{ link_to_route('purchases.index','Volver',null,array('id'=>'purchasesIndex','class'=>'btn btn-block text-nowrap btn-secondary')) }}
</div> 	
</body> 

</html>
<link href="/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
<style>
    .invoice-box { 				
        max-width: 800px; 				
        margin: auto; 				
        padding: 30px; 				
        border: 1px solid rgb(238, 238, 238); 				
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.15); 				
        font-size: 16px; 				
        line-height: 24px; 				
        font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; 				
        color: rgb(85, 85, 85); 			
    }  			
    .invoice-box table { 				
        width: 100%; 				
        line-height: inherit; 				
        text-align: left; 			
    }  			
    .invoice-box table td { 				
        padding: 5px; 				
        vertical-align: top; 			
    }  			
    .invoice-box table tr td:nth-child(2) { 				
        text-align: right; 			}  			
    .invoice-box table tr.top table td { 				
        padding-bottom: 20px; 			
    }  			
    .invoice-box table tr.top table td.title { 				
        font-size: 45px; 				
        line-height: 45px; 				
        color: rgb(51, 51, 51); 			
    }  			
    .invoice-box table tr.information table td { 				
        padding-bottom: 40px; 			
    }  			
    .invoice-box table tr.heading td { 				
        background: rgb(238, 238, 238); 				
        border-bottom: 1px solid rgb(221, 221, 221); 				
        font-weight: bold; 			
    }  			
    .invoice-box table tr.details td { 				
        padding-bottom: 20px; 			
    }  			
    .invoice-box table tr.item td { 				
        border-bottom: 1px solid rgb(238, 238, 238); 			
    }  			
    .invoice-box table tr.item.last td { 				
        border-bottom: none; 			
    }  			
    .invoice-box table tr.total td:nth-child(4) { 				
        border-top: 2px solid rgb(238, 238, 238); 				
        font-weight: bold; 			
    }  			
    @media only screen and (max-width: 600px) { 				
        .invoice-box table tr.top table td { 					
            width: 100%; 					
            display: block; 					
            text-align: center; 				
        }  				
        .invoice-box table tr.information table td { 					
            width: 100%; 					
            display: block; 					
            text-align: center; 				
        } 			
    }  			
    /** RTL **/ 			
    .invoice-box.rtl { 				
        direction: rtl; 				
        font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; 			
    }
    @media print {
        #printPageButton {
            display: none;
        }
        #invoicesIndex {
            display: none;
        }
        #breadCrumbs {
            display: none;
        }
        body  { margin: 25mm 0mm 0mm 0mm; }
    }
    @page {
        size: auto;   /* auto is the initial value */
        margin: 0;  /* this affects the margin in the printer settings */
    }
</style>

