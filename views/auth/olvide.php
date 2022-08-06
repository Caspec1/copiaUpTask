<div class="contenedor olvide">
    <?php include_once __DIR__ . '/../templates/nombre-sitio.php' ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Recupera tu acceso a UpTask</p>

        <?php include_once __DIR__ . '/../templates/alertas.php' ?>

        <form action="/olvide" method="POST" class="formulario">
            <div class="campo">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Tu E-mail">
            </div>
            <input type="submit" value="Enviar instrucciones" class="boton">
        </form>

        <div class="acciones">
            <a href="/">¿Ya tienes cuenta? Iniciar Sesión</a>
            <a href="/crear">¿Aún no tienes una cuenta? Obtener una</a>
        </div>
    </div> <!--.contenedor-sm-->
</div>