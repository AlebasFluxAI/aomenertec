{{-- Sin <style> tags: Livewire 2 sigue advirtiendo "Multiple root elements"
     al re-renderizar componentes que incluyen este partial (ej. Monitoring).
     Para eliminar el warning de raíz, dejamos la tipografía y el gradiente
     como estilos inline en los elementos mismos. Es más robusto que inyectar
     CSS dentro del árbol HTML que Livewire diff-ea. --}}
<div class="section-title mb-2 flux-section-title">

    <h2 class="text-center p3 flux-tech-title"
        style="font-family: var(--flux-tech-font, 'Inter', system-ui, sans-serif); font-weight: 600; letter-spacing: 0.005em; text-transform: none;">
        <b>
            <span class="primary-text"
                  style="background: linear-gradient(90deg, #0044A4 0%, #0C62DC 60%, #00C781 100%); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent; color: transparent;">{{$first_title}} </span>
            <span class="primary-text"
                  style="background: linear-gradient(90deg, #0044A4 0%, #0C62DC 60%, #00C781 100%); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent; color: transparent;">{{$second_title}}</span>
        </b>
    </h2>

</div>
