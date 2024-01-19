const { createApp } = Vue;

createApp({
    el: "#welcomePage",
    data(){
        return {
            dateError: false,
            locationError: false,
            maximumValueError: false,
            messageError: false,
            date: '',
            location: '',
            maximumValue: '',
            message: '',
            name: '',
            email: '',
            participants: [],
            base_url: 'http://localhost:8700/api/secret-santa/'
        };
    },
    methods: {
        addParticipant(){
            this.participants.push({ name: this.name, email: this.email });
            this.name = '';
            this.email = '';
        },
        removeParticipant(index){
            this.participants.splice(index, 1);
        },
        async sendPartyRequest(){
            try{
                const data = {
                    date: this.date,
                    location: this.location,
                    maximumValue: this.maximumValue,
                    message: this.message,
                    participants: this.participants
                };
                const response = await axios.post(this.base_url + "create-party", data);
                Swal.fire({
                    icon: "success",
                    title: "A festa foi iniciada 🎉✨",
                    text: "Agora, é necessário que os participantes acessem seus e-mails para conferir os próximos passos.",
                    confirmButtonText: "Ok!",
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
            }catch(error){
                let errorMessages = [];
                const responseErrors = error.response.data.messages;
                if(error.response.data.messages){
                    for(let propriety in responseErrors){
                        if(responseErrors.hasOwnProperty(propriety)){
                            this[propriety + 'Error'] = true;
                            errorMessages = errorMessages.concat(error.response.data.messages[propriety]);
                        }
                    }
                }else{
                    errorMessages = ['Ocorreu um erro inesperado.'];
                }
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    html: `<div class="text-danger">${errorMessages.join('</br>')}</div>`
                });
            }
        }
    },
}).mount("#welcomePage");