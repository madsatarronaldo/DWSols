<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
    
    <div style="padding-top:30px;"></div>
    <div class="container">
        <form action="{{route('userfliter')}}" method="post">
            @csrf
        <h5>Filters</h5>
        <div class="row" style="padding-top: 20px; padding-bottom:20px; background-color: #f7f7f7e0;">
            <div class="col-md">
            <input type="text" class="form-control" placeholder="Company Name" name="company_name" required>
            </div>
            <div class="col-md">
            <select class="form-select" name="status" required>
                <option value=""  hidden>Status</option>
                <option value="reserved">Reserved</option>
                <option value="incorporated">Incorporated</option>
                </select>
            </div>
            <div class="col-md">
                <select class="form-select" name="cro" required>
                <option value="" hidden>CRO</option>
                @foreach($cro as $crodata)
                <option value="{{$crodata}}">{{$crodata}}</option>
                @endforeach
                </select>
            </div>
            <div class="col-md">
            <input name="startdate" placeholder="Start Date" class="form-control" onblur="(this.type='text')" onfocus="(this.type='date') required">
            </div>
            <div class="col-md">
            <input name="enddate" placeholder="End Date" class="form-control" onblur="(this.type='text')" onfocus="(this.type='date') required">
            </div>
            <div class="col-md">
            <button type="submit" class="btn btn-primary">Submit</button>
            </div>
            </div>
        </div>
        </form>
    </div>
    </div>
    <div class="container text-center card-body">   
    <div style="padding-top:10px;"></div>
    <div>
        {{$data->links('pagination::bootstrap-5')}}
    </div> 
        <div class="text-left card-body"> 
            <table class="table caption-top">
                <caption>List of Companies</caption>
                <thead style="background-color: #121212f0; color: white;">
                    <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Status</th>
                    <th scope="col">CRO</th>
                    <th scope="col">Reg Date</th>
                    </tr>
                </thead>
                <tbody id="myTable" style="background-color:#f2f2ff;">
                    @foreach($data as $dat)
                        <tr>
                            <th scope="row">{{$dat->id}}</th>
                            <td>{{$dat->name}}</td>
                            <td>{{$dat->status}}</td>
                            <td>{{$dat->cro}}</td>
                            <td>{{$dat->reg_date}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>