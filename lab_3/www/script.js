document.getElementById("creditForm").addEventListener("submit", function(e) {
    const fullname = document.querySelector("[name='fullname']").value;
    const amount = document.querySelector("[name='amount']").value;
    alert(`Подтверждение: ${fullname}, сумма кредита: ${amount} ₽`);
});