import axios from "axios";

export const buttonsDrodown = (data, type, row) => {
    const statusBooking = row.status;
    const textButtonChange = statusBooking === 'Provisional' ? 'Confirmar' : 'Provisional';
    const dataAttrChange = statusBooking === 'Provisional' ? 'booking-confirm' : 'booking-provisional';
    const cancelOption = statusBooking === 'Confirmada' || statusBooking === 'Cancelada'  ? '' : `<li>
                            <a href="#" class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white" booking-cancel="${row.id}" onclick="dropDownAction(this)">Cancelar</a>
                        </li>`;
         return`<button id="apple-imac-27-dropdown-button" onclick="dropDownOpen(this)" data-dropdown-toggle="apple-imac-27-dropdown" class="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100" type="button">
                    <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                    </svg>
                </button>
                <div id="apple-imac-27-dropdown" class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                    <ul class="py-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="apple-imac-27-dropdown-button">
                        <li>
                            <a href="/booking/${row.id}/edit" class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white" booking-edit="${row.id}">Editar</a>
                        </li>
                        ${cancelOption}
                        <li>
                            <a href="#" class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white" ${dataAttrChange}="${row.id}" onclick="dropDownAction(this)">${textButtonChange}</a>
                        </li>
                    </ul>
                    <div class="py-1">
                        <a href="#" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white" booking-view="${row.id}" onclick="dropDownAction(this)">Ver</a>
                    </div>
                </div>`;
}

export const clientFetch = async (method = 'GET', payload, endpoint = 'bookings' ) => {
    try {
        return await axios.request({
            url: `${import.meta.env.VITE_BACKEND_URL}/api/${endpoint}`,
            method: method,
            data: payload
        });
    }catch (error){
        throw error;
    }
}

export const modalContent = ({ arrival_date,  departure_date, number_nights, number_people, status, total_amount, name, document_number}) => {
    return `<div>
                <p><strong>Datos del huésped principal:</strong></p>
                <p>Nombre: ${name}</p>
                <p>N° documento: ${document_number}</p>
                </div>
                <p>Llegada: ${arrival_date}</p>
                <p>Salida: ${departure_date}</p>
                <p>Cantidad de noches: ${number_nights}</p>
                <p>Número de huéspedes: ${number_people}</p>
                <p>Estado de reservación: ${status}</p>
                <p>Total reservación <strong>$${total_amount}</strong></p>
            </div>`;
}
