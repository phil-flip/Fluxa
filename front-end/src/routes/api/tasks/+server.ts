import type {RequestEvent, RequestHandler} from "@sveltejs/kit";
import {api} from "$src/api/ServerApiClient";
import {superValidate} from 'sveltekit-superforms/server';
import {schema} from "$src/form/create-task";

export const POST: RequestHandler = async (request: RequestEvent) => {
    const form = await superValidate(request, schema);

    console.log(form.data);
    const task = await api.postTask({
        ...form.data
    });

    return Response.json(task);
};
