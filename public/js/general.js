function confirmAction(completion) {
    if(confirm(completion.concat('\n¿Está seguro/a de que desea continuar?')))
        return true;
    else
        return false;
}
