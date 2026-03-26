<!DOCTYPE html>
<html> 
<head>
    <title>Tela inicial</title>
</head>
<body>
    <p>Bem-vindo</p>
    <form method = "POST" action =" {{ route('usuario.store')}}">
    @csrf
    <p>nome <p>
    <input type = "text" name ="nome" >
    <p>email<p>
    <input type = "email" email ="email">
    <p>senha<p>
    <input type = "text" password = "password">
    <button type = "submit" > Salvar</button> 
</body>           
</html>  