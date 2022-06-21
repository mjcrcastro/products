<!DOCTYPE html> 
<html> 
    <head> 		
        <meta charset="utf-8" /> 		
        <title>Producto</title>  		 	
    </head>  	
    <body> 

        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="row align-items-center">
                        <div class="col-sm-8 align-items-center">
                            <div class="row"><b>Codigo: </b> {{ $product->barcode }}</div>
                            <div class="row"><b>Descripción:</b> {{ $product->description }}</div>
                            <div id="productPrice" class="row"><b>Precio: C$</b> {{ number_format($product->price,2) }}</div>
                        </div>
                        <div class="col-sm-4">
                            <img src="https://chart.googleapis.com/chart?chs=200x200&cht=qr&&chld=H&chl={{ $product->barcode }}" alt="QR code" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
            <button id="printPageButton" class='btn btn-block text-nowrap btn-primary' onClick="window.print();">Imprimir</button>
            {{ link_to_route('products.index','Volver',null,array('id'=>'productsIndex','class'=>'btn col-12 text-nowrap btn-outline-secondary')) }}
        </div>

    </body> 
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
            #productsIndex {
                display: none;
            }
            #breadCrumbs {
                display: none;
            }
            #productPrice {
                display: none;
            }
            body  { margin: 25mm 0mm 0mm 0mm; }
        }
        @page {
            size: auto;   /* auto is the initial value */
            margin: 0;  /* this affects the margin in the printer settings */
        }
    </style>


</html>
