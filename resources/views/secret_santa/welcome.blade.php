<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Secret Santa</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

        <!-- JavaScript -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

        <!--Vue-->
        <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>

        <!-- Axios -->
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

        <!-- SweetAlert2 --> 
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    </head>
    <body id="welcomePage" class="container">

        <div class="row">

            <div class="col-md-12">
                <h3>Seja bem-vindo ao Secret Santa.</h3>
            </div>
            <div class="col-md-12">
                <p>O Secret Santa é um site para criar a brincadeira de amigo secreto, junte os seus amigos e vamos nessa!</p>
            </div>
            
            <div class="col-md-12">
                <h3>Como Funciona?</h3>
            </div>
            <div class="col-md-12">
                <p>Para iniciar a brincadeira, é necessário preencher algumas informações. Abaixo, você encontrará detalhes como data, local, valor máximo, mensagem, nomes e e-mails.</p>
            </div>
            <div class="col-md-12">
                <p>Ao preencher as informações necessárias, incluindo o número de participantes, clique no botão "Começar Festa". Em seguida, um e-mail será enviado contendo as instruções para a celebração, acompanhadas dos nomes dos respectivos amigos secretos. Dentro do e-mail, você encontrará um botão especial chamado "Mágico". Ao clicar nele, será direcionado para a sua lista de desejos, onde poderá especificar os presentes desejados. Complete a lista e clique em "Enviar Lista de Desejos". Após esse procedimento, o seu amigo secreto receberá por e-mail a sua lista de desejos.</p>
            </div>
            
        </div>

        <div class="row mt-2">
            <div class="col-md-4">
                <label for="date" class="font-weight-bold">{{ __('Data') }}</label>
                <input type="date" name="date" class="form-control" v-model="date" :class="{ 'is-invalid': dateError }">
            </div>
            <div class="col-md-4">
                <label for="location" class="font-weight-bold">{{ __('Local') }}</label>
                <input type="text" name="location" class="form-control" v-model="location" :class="{ 'is-invalid': locationError }">
            </div>
            <div class="col-md-4">
                <label for="maximumValue" class="font-weight-bold">{{ __('Valor Máximo') }}</label>
                <input type="number" name="maximumValue" class="form-control" v-model="maximumValue" :class="{ 'is-invalid': maximumValueError }">
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-12">
                <textarea name="message" class="form-control" id="" cols="30" rows="10" style="resize: none;" v-model="message" :class="{ 'is-invalid': messageError }"></textarea>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-6 text-center">

                <label for="name" class="font-weight-bold">{{ __("Nome") }}</label>
                <input type="text" class="form-control" name="name" v-model="name" autocomplete="off">

                <label for="name" class="font-weight-bold">{{ __("E-mail") }}</label>
                <input type="text" class="form-control" name="email" v-model="email" autocomplete="off">

                <button @click="addParticipant" class="btn btn-danger col-md-5 m-1">
                    Adicionar Participante
                </button>

                <button @click="sendPartyRequest" class="btn btn-danger col-md-5 m-1">
                    Começar Festa
                </button>
                
            </div>

            <div class="col-md-6">
                <table class="table mt-2">
                    <thead>
                        <th>
                            {{ __('Nome') }}
                        </th>
                        <th colspan="2">
                            {{ __('E-mail') }}
                        </th>
                    </thead>
                    <tbody>
                        <tr v-for="(participant, index) in participants" :key="index">
                            <td>
                                <input type="text" class="form-control" name="name" v-model="participant.name" readonly> 
                            </td>
                            <td>
                                <input type="text" class="form-control" name="email" v-model="participant.email" readonly>
                            </td>
                            <td>
                                <button @click="removeParticipant(index)" type="button" class="close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </body>

    <script src="{{ asset('javascript/addParticipant.js') }}"></script>

</html>
