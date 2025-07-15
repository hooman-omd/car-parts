<style>
    #success-alert {
        opacity: 1;
        transition: opacity 1s ease-out;
    }

    #success-alert.fade-out {
        opacity: 0;
    }
</style>

<div class="alert alert-success my-4" role="alert" id="success-alert">
    {{$slot}} <i class="fa fa-check" aria-hidden="true"></i>
</div>

<script>
    setTimeout(() => {
        const alert = document.getElementById('success-alert');
        alert.classList.add('fade-out');
        
        // Remove after the fade-out transition ends (1s here)
        setTimeout(() => {
            alert.remove();
        }, 1000);
    }, 4000);
</script>
