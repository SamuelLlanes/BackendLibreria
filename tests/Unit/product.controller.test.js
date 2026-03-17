const { getProducts } = require('../../controllers/productController');
const productRepository = require('../../repositories/productRepository');

jest.mock('../../repositories/productRepository');

describe('Unit Test: Product Controller', () => {
    it('Debe retornar una lista de productos y status 200', async () => {
        const mockProducts = [{ id: 1, name: 'Pintura Látex', price: 25.0 }];
        productRepository.findAll.mockResolvedValue(mockProducts);

        const req = {};
        const res = {
            status: jest.fn().mockReturnThis(),
            json: jest.fn()
        };

        await getProducts(req, res);

        expect(res.status).toHaveBeenCalledWith(200);
        expect(res.json).toHaveBeenCalledWith(mockProducts);
    });
});