const request = require('supertest');
const app = require('../../app'); 

describe('Integration Test: API Endpoints', () => {
    
    it('POST /api/products - Debe crear un producto correctamente', async () => {
        const newProduct = {
            name: "Barniz Brillante",
            price: 45.50,
            categoryId: 1
        };

        const response = await request(app)
            .post('/api/products')
            .send(newProduct)
            .set('Authorization', 'Bearer TOKEN_DE_PRUEBA');

        expect(response.status).toBe(201);
        expect(response.body.name).toBe("Barniz Brillante");
    });

    it('GET /api/categories - Debe retornar todas las categorías', async () => {
        const response = await request(app).get('/api/categories');
        expect(response.status).toBe(200);
        expect(Array.isArray(response.body)).toBe(true);
    });
});